<?php

namespace Perna\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Document\GoogleCalendar;
use Perna\Document\GoogleEvent;
use Perna\Document\GoogleEventCache;
use Perna\Document\User;
use Perna\Hydrator\GoogleCalendarHydrator;
use Perna\Hydrator\GoogleEventHydrator;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;

/**
 * Class GoogleCalendarService
 *
 * @author      Jannik Portz
 * @package     Perna\Service
 */
class GoogleCalendarService {

	/**
	 * The GoogleAuthenticationService
	 * @var       GoogleAuthenticationService
	 */
	protected $googleAuthenticationService;

	/**
	 * @var       GoogleCalendarHydrator
	 */
	protected $googleCalendarHydrator;

	/**
	 * @var       GoogleEventHydrator
	 */
	protected $googleEventHydrator;

	/**
	 * @var       GoogleCalendarEventsService
	 */
	protected $googleCalendarEventsService;

	/**
	 * @var       DocumentManager
	 */
	protected $documentManager;

	public function __construct ( GoogleAuthenticationService $googleAuthenticationService,
		GoogleCalendarHydrator $googleCalendarHydrator, GoogleEventHydrator $googleEventHydrator,
		GoogleCalendarEventsService $googleCalendarEventsService, DocumentManager $documentManager ) {
		$this->googleAuthenticationService = $googleAuthenticationService;
		$this->googleCalendarHydrator = $googleCalendarHydrator;
		$this->googleEventHydrator = $googleEventHydrator;
		$this->googleCalendarEventsService = $googleCalendarEventsService;
		$this->documentManager = $documentManager;
	}

	/**
	 * Creates a Google Calendar Service for the specified User
	 * @param     User      $user           The User for which to create the Service
	 * @return    \Google_Service_Calendar  The Google Calendar Service for the specified User
	 */
	public function createGoogleCalendarService ( User $user ) : \Google_Service_Calendar {
		$client = $this->googleAuthenticationService->createAuthorizedClient( $user );
		return new \Google_Service_Calendar( $client );
	}

	/**
	 * Retrieves a list of all available GoogleCalendatrs of the current user
	 * @param     User      $user     The user whose calendars to retrieve
	 * @return    GoogleCalendar[]    All available calendars associated with the User
	 */
	public function getCalendars ( User $user ) {
		$service = $this->createGoogleCalendarService( $user );
		$savedCalendars = $user->getGoogleCalendars() ?? [];

		$results = $service->calendarList->listCalendarList([
			'maxResults' => 250, // Max available size
			'minAccessRole' => 'reader',
			'showDeleted' => false,
			'showHidden' => false
		]);

		$calendars = [];
		$calendarIds = [];
		$hydrator = $this->googleCalendarHydrator;

		foreach ( $results as $result ) {
			/** @var \Google_Service_Calendar_CalendarListEntry $result */
			$id = $result->getId();

			// Try to get an existing GoogleCalendar or create a new one
			$calendar = null;
			foreach ( $savedCalendars as $savedCalendar ) {
				if ( $savedCalendar->getId() === $id ) {
					$calendar = $savedCalendar;
					break;
				}
			}

			if ( $calendar === null )
				$calendar = new GoogleCalendar();

			$calendars[] = $hydrator->hydrateFromGoogleCalendarEntry( $result, $calendar );
			$calendarIds[] = $calendar->getId();
		}

		/** Remove Event caches of removed calendars */
		foreach ( $savedCalendars ?? [] as $savedCalendar ) {
			if ( !in_array( $savedCalendar->getId(), $calendarIds ) && $savedCalendar->getEventCache() instanceof GoogleEventCache )
				$this->documentManager->remove( $savedCalendar->getEventCache() );
		}

		$user->setGoogleCalendars( $calendars );
		$this->documentManager->flush();

		return $calendars;
	}

	/**
	 * Generates an Event by an event text
	 * @param     User      $user       The current user
	 * @param     string    $eventText  The text for the event (e.g. spoken event text)
	 * @param     string    $calendar   The id of the calendar to which to add the event. Defaults to the User's primary calendar
	 *
	 * @return    GoogleEvent           The newly created Google Event
	 */
	public function quickAddEvent ( User $user, string $eventText, string $calendar = 'primary' ) {
		$service = $this->createGoogleCalendarService( $user );
		$event = $service->events->quickAdd( $calendar, $eventText );
		return $this->googleEventHydrator->hydrateFromGoogleEvent( $event, new GoogleEvent() );
	}

	/**
	 * Retrieves the Events for the specified user in the specified calendars
	 * @param     User      $user         The user whose events to retrieve
	 * @param     string[]  $calendarIds  Ids of the calendars
	 * @return    GoogleEvent[]           The events of the specified User in the specified calendars
	 */
	public function getEvents ( User $user, array $calendarIds ) : array {
		$service = $this->createGoogleCalendarService( $user );
		$eventsService = $this->googleCalendarEventsService;
		$eventsService->setGoogleService( $service );

		// Refresh calendars when error has occurred
		$eventsService->getEventManager()->attach( GoogleCalendarEventsService::EVENT_CALENDAR_ERROR, function () use ($user) {
			$this->getCalendars( $user );
		} );

		return $eventsService->getEvents( $user, $calendarIds );
	}

	public function handleNotification ( string $token, string $resourceId ) {
		$qb = $this->documentManager->createQueryBuilder();
		$qb->find( GoogleCalendar::class );
		$qb->field('eventCache.watchSessionToken')->equals( $token );
		$calendar = $qb->getQuery()->execute();

		if ( !$calendar instanceof GoogleCalendar )
			throw new UnprocessableEntityException("A cache with the specified id does not exist.");

		$qb = $this->documentManager->createQueryBuilder();
		$qb->find( User::class );
		$qb->field('googleCalendars.$id')->equals( $calendar->getId() );
		$user = $qb->getQuery()->execute();

		if ( !$user instanceof User )
			throw new UnprocessableEntityException("The user for the calendar could not be found.");
	}
}