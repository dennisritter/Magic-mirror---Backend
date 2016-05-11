<?php

namespace Perna\Service;

use Perna\Document\GoogleCalendar;
use Perna\Document\User;
use Perna\Hydrator\GoogleCalendarHydrator;

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

	public function __construct ( GoogleAuthenticationService $googleAuthenticationService, GoogleCalendarHydrator $googleCalendarHydrator ) {
		$this->googleAuthenticationService = $googleAuthenticationService;
		$this->googleCalendarHydrator = $googleCalendarHydrator;
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
}