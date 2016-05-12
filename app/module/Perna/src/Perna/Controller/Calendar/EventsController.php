<?php

namespace Perna\Controller\Calendar;

use Perna\Hydrator\GoogleEventHydrator;
use Perna\InputFilter\EventQuickAddInputFilter;

class EventsController extends AbstractCalendarController {

	public function post () {
		$this->assertAccessToken();
		$user  = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
		$data  = $this->validateIncomingData( EventQuickAddInputFilter::class );
		$event = $this->googleCalendarService->quickAddEvent( $user, $data['text'], $data['calendar'] ?? 'primary' );

		return $this->createDefaultViewModel( $this->extractObject( GoogleEventHydrator::class, $event ) );
	}
}