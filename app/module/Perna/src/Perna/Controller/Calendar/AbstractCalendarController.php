<?php

namespace Perna\Controller\Calendar;

use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Service\AuthenticationService;
use Perna\Service\GoogleCalendarService;

abstract class AbstractCalendarController extends AbstractAuthenticatedApiController {

	/**
	 * The GoogleCalendarService
	 * @var       GoogleCalendarService
	 */
	protected $googleCalendarService;

	public function __construct( AuthenticationService $authenticationService, GoogleCalendarService $googleCalendarService ) {
		parent::__construct( $authenticationService );
		$this->googleCalendarService = $googleCalendarService;
	}

}