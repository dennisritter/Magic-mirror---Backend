<?php

namespace Perna\Controller\Calendar;

use Perna\Document\GoogleEvent;
use Perna\Hydrator\GoogleEventHydrator;
use Perna\InputFilter\EventQuickAddInputFilter;
use Swagger\Annotations as SWG;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;

class EventsController extends AbstractCalendarController {

	/**
	 * @SWG\Post(
	 *   path="/calendar/events",
	 *   summary="Quick add event",
	 *   description="Adds a new Google Calendar Event using Quick Add.
	 *    The specified text is interpreted by Google and converted to an actual event.
	 *    Be aware that Google Quick Add only works well if the specified text is in English and the Google User's language setting is set to English.",
	 *   operationId="quickAddEvent",
	 *   tags={"calendar"},
	 *   @SWG\Parameter(
	 *    in="body",
	 *    name="body",
	 *    @SWG\Schema(
	 *      required={"text"},
	 *      @SWG\Property(property="text", type="string", description="The textual event representation to be converted to an actual event"),
	 *      @SWG\Property(property="calendar", type="string",
	 *        description="The id of the calendar to which to add the new Event.
	 *        Defaults to the primary calendar.", default="primary")
	 *      )
	 *   ),
	 *   @SWG\Parameter(ref="#/parameters/accessToken"),
	 *   @SWG\Response(
	 *    response="201",
	 *    description="The event has successfully been added to the calendar. Be aware that this does not mean that all parameters have been set to the desired values.",
	 *    @SWG\Schema(
	 *      @SWG\Property(property="success", type="boolean", default=true),
	 *      @SWG\Property(property="data", ref="GoogleEvent", description="The newly creates google event")
	 *    )
	 *  ),
	 *  @SWG\Response(response="403", ref="#/responses/403"),
	 *  @SWG\Response(response="422", ref="#/responses/422")
	 * )
	 */
	public function post () {
		$this->assertAccessToken();
		$user  = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
		$data  = $this->validateIncomingData( EventQuickAddInputFilter::class );
		$event = $this->googleCalendarService->quickAddEvent( $user, $data['text'], $data['calendar'] ?? 'primary' );

		return $this->createDefaultViewModel( $this->extractObject( GoogleEventHydrator::class, $event ) );
	}

	/**
	 * @SWG\Get(
	 *   path="/calendar/events",
	 *   summary="Get Events",
	 *   description="Returns today's upcoming events for the specified calendar. Currently debounced by 10 minutes (Events will actually refresh every 10 minutes).",
	 *   operationId="getEvents",
	 *   tags={"calendar"},
	 *   @SWG\Parameter(
	 *    in="query",
	 *    name="calendarIds",
	 *    type="string",
	 *    description="Comma separated list of calendar ids. Unknown ids will be ignored.",
	 *    default="calendar1,calendar2,calendar3"
	 *   ),
	 *   @SWG\Parameter(ref="#/parameters/accessToken"),
	 *   @SWG\Response(
	 *    response="200",
	 *    description="The events have successfully be retrieved",
	 *    @SWG\Schema(
	 *      @SWG\Property(property="success", type="boolean", default=true),
	 *      @SWG\Property(
	 *        property="data",
	 *        type="array",
	 *        description="Array of events in the specified calendars",
	 *        @SWG\Items(ref="GoogleEvent")
	 *      )
	 *    )
	 *   ),
	 *   @SWG\Response(response="422", ref="#/responses/422"),
	 *   @SWG\Response(response="403", ref="#/responses/403")
	 * )
	 */
	public function get () {
		$this->assertAccessToken();
		$user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );

		$calendarIds = $this->params()->fromQuery('calendarIds', '');
		if ( empty( $calendarIds ) || preg_match('/^\s+$/', $calendarIds) )
			throw new UnprocessableEntityException("You must specify at least one calendar");

		$calendarIds = explode(',', $calendarIds);
		foreach ( $calendarIds as &$id )
			$id = trim($id);

		$events = $this->googleCalendarService->getEvents( $user, $calendarIds );
		return $this->createDefaultViewModel( $this->extractObject( GoogleEventHydrator::class, $events ) );
	}
}