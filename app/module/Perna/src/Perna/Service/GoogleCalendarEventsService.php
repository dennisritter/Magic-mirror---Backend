<?php

namespace Perna\Service;

use Perna\Document\GoogleCalendar;
use Perna\Document\GoogleEvent;
use Perna\Document\GoogleEventCache;
use Perna\Document\User;
use Perna\Hydrator\GoogleEventHydrator;

/**
 * Service dealing with Google Calendar Events
 *
 * @author      Jannik Portz
 * @package     Perna\Service
 */
class GoogleCalendarEventsService {

	// TODO: move to config
	const CALLBACK = 'http://api.perna.dev/v1/calendar/notify';

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

	public function __construct ( \Google_Service_Calendar $googleService, GoogleEventHydrator $googleEventHydrator, GUIDGenerator $guidGenerator ) {
		$this->googleService = $googleService;
		$this->googleEventHydrator = $googleEventHydrator;
		$this->guidGenerator = $guidGenerator;
	}

	/**
	 * Retrieves today's upcoming events of the specified users in the specified calendars.
	 * @param     User      $user         The User whose calendar events to retrieve
	 * @param     string[]  $calendarIds  Sequential array of calendar ids
	 * @return    GoogleEvent[]           Today's upcoming events for the specified calendar
	 */
	public function getEvents ( User $user, array $calendarIds ) : array {

	}

	/**
	 * Retrieves today's events for the specified calendar
	 * @param     GoogleCalendar      $calendar   The calendar whose events to retrieve
	 * @return    GoogleEvent[]                   Today's upcoming events of the specified calendar
	 */
	protected function getCalendarEvents ( GoogleCalendar $calendar ) : array {

	}

	/**
	 * Initializes the event cache for the specified calendar
	 * @param     GoogleCalendar      $calendar   The calendar for which to initialize the event cache
	 */
	protected function initializeEventCache ( GoogleCalendar $calendar ) {
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
			$events[] = $this->googleEventHydrator->hydrateFromGoogleEvent( $result, new GoogleEvent() );
		}

		$cache = new GoogleEventCache();
		$cache->setEvents( $events );

		$channel = new \Google_Service_Calendar_Channel();
		$channel->setId( $this->guidGenerator->generateGUID() );
		$channel->setType('web_hook');
		$channel->setAddress( self::CALLBACK );
	}
}