<?php

namespace Perna\Service;

use Perna\Document\GoogleCalendar;
use Perna\Document\GoogleEvent;
use Perna\Document\User;
use Perna\Hydrator\GoogleCalendarHydrator;
use Perna\Hydrator\GoogleEventHydrator;

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

	public function __construct ( GoogleAuthenticationService $googleAuthenticationService,
		GoogleCalendarHydrator $googleCalendarHydrator, GoogleEventHydrator $googleEventHydrator, GoogleCalendarEventsService $googleCalendarEventsService ) {
		$this->googleAuthenticationService = $googleAuthenticationService;
		$this->googleCalendarHydrator = $googleCalendarHydrator;
		$this->googleEventHydrator = $googleEventHydrator;
		$this->googleCalendarEventsService = $googleCalendarEventsService;
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
		$results = $service->calendarList->listCalendarList([
			'maxResults' => 250, // Max available size
			'minAccessRole' => 'reader',
			'showDeleted' => false,
			'showHidden' => false
		]);

		$calendars = [];
		$hydrator = $this->googleCalendarHydrator;
		foreach ( $results as $result ) {
			$calendars[] = $hydrator->hydrateFromGoogleCalendarEntry( $result, new GoogleCalendar() );
		}

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
		return $eventsService->getEvents( $user, $calendarIds );
	}
}