<?php

namespace Perna\Service;
use Perna\Document\User;

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

	public function __construct ( GoogleAuthenticationService $googleAuthenticationService ) {
		$this->googleAuthenticationService = $googleAuthenticationService;
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
		$results = $results;
		return $results;
	}

}