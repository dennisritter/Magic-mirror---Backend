<?php

namespace Perna\Controller\Calendar;

use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Service\AuthenticationService;
use Perna\Service\GoogleCalendarService;

/**
 * Controller for Calendars endpoint
 *
 * @author      Jannik Portz
 * @package     Perna\Controller\Calendar
 */
class CalendarsController extends AbstractAuthenticatedApiController {

	/**
	 * @var       GoogleCalendarService
	 */
	protected $googleCalendarService;

	public function __construct( AuthenticationService $authenticationService, GoogleCalendarService $googleCalendarService ) {
		parent::__construct( $authenticationService );
		$this->googleCalendarService = $googleCalendarService;
	}

	public function get () {
		$this->assertAccessToken();
		$user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
		$results = $this->googleCalendarService->getCalendars( $user );
		return $this->createDefaultViewModel([
			'success' => true
		]);
	}
}