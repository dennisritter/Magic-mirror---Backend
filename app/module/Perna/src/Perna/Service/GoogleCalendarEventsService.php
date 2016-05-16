<?php

namespace Perna\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Document\GoogleCalendar;
use Perna\Document\GoogleEvent;
use Perna\Document\GoogleEventCache;
use Perna\Document\User;
use Perna\Filter\UpcomingTodayEventsFilter;
use Perna\Hydrator\GoogleEventHydrator;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;

/**
 * Service dealing with Google Calendar Events
 *
 * @author      Jannik Portz
 * @package     Perna\Service
 */
class GoogleCalendarEventsService implements EventManagerAwareInterface {

	use EventManagerAwareTrait;

	const EVENT_CALENDAR_ERROR = "CALENDAR_ERROR";

	/**
	 * The authorized Google_Calendar_Service
	 * @var       \Google_Service_Calendar
	 */
	protected $googleService;

	/**
	 * Hydrator for GoogleEvents
	 * @var       GoogleEventHydrator
	 */
	protected $googleEventHydrator;

	/**
	 * GUID Generator
	 * @var       GUIDGenerator
	 */
	protected $guidGenerator;

	/**
	 * The DocumentManager
	 * @var       DocumentManager
	 */
	protected $documentManager;

	public function __construct ( GoogleEventHydrator $googleEventHydrator, GUIDGenerator $guidGenerator, DocumentManager $documentManager ) {
		$this->googleEventHydrator = $googleEventHydrator;
		$this->guidGenerator = $guidGenerator;
		$this->documentManager = $documentManager;
	}

	/**
	 * Retrieves today's upcoming events of the specified users in the specified calendars.
	 * @param     User      $user         The User whose calendar events to retrieve
	 * @param     string[]  $calendarIds  Sequential array of calendar ids
	 * @return    GoogleEvent[]           Today's upcoming events for the specified calendar
	 *
	 * @throws    UnprocessableEntityException    If a calendar could not be found
	 */
	public function getEvents ( User $user, array $calendarIds ) : array {
		$calendars = [];
		foreach ( $user->getGoogleCalendars() as $calendar ) {
			if ( in_array( $calendar->getId(), $calendarIds ) )
				$calendars[] = $calendar;
		}

		if ( count( $calendars ) < 1 )
			return [];

		$events = [];
		$calendarError = false;
		foreach ( $calendars as $calendar ) {
			try {
				$calendarEvents = $this->getCalendarEvents( $calendar );

				if ( count( $calendarEvents ) > 0 )
					$events = array_merge( $events, $calendarEvents->toArray() );
			} catch ( \Google_Service_Exception $e ) {
				$calendarError = true;
			}
		}

		// Trigger a calendar error event to inform listeners that there might be any issues
		if ( $calendarError )
			$this->getEventManager()->trigger( self::EVENT_CALENDAR_ERROR, $this, [] );

		$filter = new UpcomingTodayEventsFilter();
		return $filter->filter( $events );
	}

	/**
	 * Retrieves today's events for the specified calendar
	 * @param     GoogleCalendar      $calendar   The calendar whose events to retrieve
	 * @return    Collection          Today's upcoming events of the specified calendar
	 *
	 * @throws    \Google_Service_Exception If events could not be retrieved
	 */
	protected function getCalendarEvents ( GoogleCalendar $calendar ) : Collection {
		$cache = $calendar->getEventCache();
		if ( !$cache instanceof GoogleEventCache || $cache->getWatchSessionExpiration() <= new \DateTime('now') ) {
			$cache = $this->initializeEventCache( $calendar );
		}

		return $cache->getEvents();
	}

	/**
	 * Initializes the event cache for the specified calendar
	 * @param     GoogleCalendar      $calendar   The calendar for which to initialize the event cache
	 * @return    GoogleEventCache                The newly created event cache
	 *
	 * @throws    \Google_Service_Exception       If the events for the calendar could not be retrieved
	 */
	protected function initializeEventCache ( GoogleCalendar $calendar ) : GoogleEventCache {
		$now = new \DateTime('now');
		$tomorrow = clone $now;
		$tomorrow->add( new \DateInterval('P1D') );
		$tomorrow->setTime(0,0,0);

		$params = [
			'maxResults' => 2500,
			'singleEvents' => true,
			'timeMin' => $now->format( \DateTime::RFC3339 ),
			'timeMax' => $tomorrow->format( \DateTime::RFC3339 )
		];

		$results = $this->googleService->events->listEvents( $calendar->getId(), $params );
		$events = [];
		foreach ( $results as $result ) {
			/** @var \Google_Service_Calendar_Event $result */
			$event = $this->googleEventHydrator->hydrateFromGoogleEvent( $result, new GoogleEvent() );
			$event->setCalendarId( $calendar->getId() );
			$events[] = $event;
		}

		$cache = new GoogleEventCache();
		$cache->setEvents( $events );
		$cache->setCreated( $now );
		$cache->setWatchSessionToken( $this->guidGenerator->generateGUID() );
		$expires = new \DateTime('now');
		$expires->add( new \DateInterval('PT10M') );
		$cache->setWatchSessionExpiration( $expires );

		$this->documentManager->persist( $cache );

		$oldCache = $calendar->getEventCache();
		if ( $oldCache instanceof GoogleEventCache )
			$this->documentManager->remove( $oldCache );

		$calendar->setEventCache( $cache );
		$this->documentManager->flush();

		return $cache;
	}

	public function setGoogleService ( \Google_Service_Calendar $service ) {
		$this->googleService = $service;
	}
}