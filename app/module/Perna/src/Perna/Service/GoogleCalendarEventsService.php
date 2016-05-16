<?php

namespace Perna\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Document\GoogleCalendar;
use Perna\Document\GoogleEvent;
use Perna\Document\GoogleEventCache;
use Perna\Document\User;
use Perna\Filter\UpcomingTodayEventsFilter;
use Perna\Hydrator\GoogleEventHydrator;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;

/**
 * Service dealing with Google Calendar Events
 *
 * @author      Jannik Portz
 * @package     Perna\Service
 */
class GoogleCalendarEventsService {

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

	public function __construct ( \Google_Service_Calendar $googleService, GoogleEventHydrator $googleEventHydrator, GUIDGenerator $guidGenerator, DocumentManager $documentManager ) {
		$this->googleService = $googleService;
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

		if ( count( $calendars ) > 0 )
			return [];

		$events = [];
		foreach ( $calendars as $calendar ) {
			$calendarEvents = $this->getCalendarEvents( $calendar );

			if ( count( $calendarEvents ) > 0 )
				$events = array_merge( $events, $calendarEvents );
		}

		$filter = new UpcomingTodayEventsFilter();
		return $filter->filter( $events );
	}

	/**
	 * Retrieves today's events for the specified calendar
	 * @param     GoogleCalendar      $calendar   The calendar whose events to retrieve
	 * @return    GoogleEvent[]                   Today's upcoming events of the specified calendar
	 */
	protected function getCalendarEvents ( GoogleCalendar $calendar ) : array {
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