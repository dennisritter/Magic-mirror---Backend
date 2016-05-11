<?php

namespace Perna\Controller\Calendar;

use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Hydrator\GoogleCalendarHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\GoogleCalendarService;
use Swagger\Annotations as SWG;

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

	/**
	 * @SWG\Get(
	 *   path="/calendar/calendars",
	 *   summary="Google-Calendar-List",
	 *   description="Lists up all calendars of the specified user.",
	 *   operationId="getCalendars",
	 *   tags={"calendar"},
	 *   @SWG\Response(
	 *    response="200",
	 *    description="The User's calendars have successfully been retrieved.",
	 *    @SWG\Schema(
	 *      type="array",
	 *      @SWG\Items( ref="GoogleCalendar" )
	 *    )
	 *   ),
	 *   @SWG\Response(
	 *    response="403",
	 *    description="If the user has not authenticated at Google or access has been revoked"
	 *   )
	 * )
	 */
	public function get () {
		$this->assertAccessToken();
		$user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
		$calendars = $this->googleCalendarService->getCalendars( $user );
		return $this->createDefaultViewModel( $this->extractObject( GoogleCalendarHydrator::class, $calendars ) );
	}
}