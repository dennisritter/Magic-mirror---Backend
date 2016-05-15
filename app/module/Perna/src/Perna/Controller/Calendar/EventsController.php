<?php

namespace Perna\Controller\Calendar;

use Perna\Document\GoogleEvent;
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

	/**
	 * @SWG\Get(
	 *   path="/calendar/events",
	 *   summary="Get Events",
	 *   description="Serves test data for events.",
	 *   operationId="getEvents",
	 *   tags={"calendar"},
	 *   @SWG\Response(
	 *    response="200",
	 *    description="The events have successfully be retrieved",
	 *    @SWG\Schema(
	 *      type="array",
	 *      @SWG\Items(ref="GoogleEvent")
	 *   )
	 *  )
	 * )
	 */
	public function get () {
		$this->assertAccessToken();
		$events = $this->generateTestEvents();
		return $this->createDefaultViewModel( $this->extractObject( GoogleEventHydrator::class, $events ) );
	}

	protected function generateTestEvents () : array {
		$events = [];

		$events[] = $this->generateEvent('e1', 'Allday event', new \DateTime('00:00:00'), new \DateTime('23:59:59'), true);

		$e = $this->generateEvent('e2', 'Breakfast', new \DateTime('10:00'), new \DateTime('11:30'));
		$e->setLocation('Tiffany');
		$events[] = $e;

		$e = $this->generateEvent('e3', 'Lunch', new \DateTime('11:15'), new \DateTime('12:30'));
		$e->setAttendees(['Nathalie Junker', 'Dennis Ritter', 'Johannes Knauft']);
		$events[] = $e;

		$e = $this->generateEvent('e4', 'Dinner', new \DateTime('21:00'), new \DateTime('01:30'));
		$e->getEndTime()->add( new \DateInterval('P1D') );
		$events[] = $e;

		return $events;
	}

	protected function generateEvent ( string $id, string $summary, \DateTime $startTime, \DateTime $endTime, bool $transparent = false ) : GoogleEvent {
		$e = new GoogleEvent();
		$e->setId( $id );
		$e->setSummary( $summary );

		$start = new \DateTime('now');
		$end = clone $start;
		$start->setTime( (int) $startTime->format('H'), (int) $startTime->format('i'), (int) $startTime->format('s') );
		$end->setTime( (int) $endTime->format('H'), (int) $endTime->format('i'), (int) $endTime->format('s') );

		$e->setStartTime( $start );
		$e->setEndTime( $end );
		$e->setTransparency( $transparent ? 'transparent' : 'opaque' );

		return $e;
	}
}