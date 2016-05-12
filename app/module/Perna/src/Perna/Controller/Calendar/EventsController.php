<?php

namespace Perna\Controller\Calendar;

use Perna\Hydrator\GoogleEventHydrator;
use Perna\InputFilter\EventQuickAddInputFilter;
use Swagger\Annotations as SWG;

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
	 *   @SWG\Response(
	 *    response="201",
	 *    description="The event has successfully been added to the calendar. Be aware that this does not mean that all parameters have been set to the desired values.",
	 *    @SWG\Schema(ref="GoogleEvent", description="The newly creates google event")
	 *  )
	 * )
	 */
	public function post () {
		$this->assertAccessToken();
		$user  = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
		$data  = $this->validateIncomingData( EventQuickAddInputFilter::class );
		$event = $this->googleCalendarService->quickAddEvent( $user, $data['text'], $data['calendar'] ?? 'primary' );

		return $this->createDefaultViewModel( $this->extractObject( GoogleEventHydrator::class, $event ) );
	}
}