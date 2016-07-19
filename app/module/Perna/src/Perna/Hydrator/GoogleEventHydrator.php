<?php

namespace Perna\Hydrator;
use Perna\Document\GoogleEvent;

/**
 * Hydrator for Google Events
 *
 * @author      Jannik Portz
 * @package     Perna\Hydrator
 */
class GoogleEventHydrator extends AbstractHydrator {
	
	public function extract( $object ) {
		$attendees = [];
		foreach ($object->getAttendees() as $attendee) {
			if ( !empty($attendee) )
				$attendees[] = $attendee;
		}

		/** @var GoogleEvent $object */
		return [
			'id' => $object->getId(),
			'description' => $object->getDescription(),
			'location' => $object->getLocation(),
			'transparency' => $object->getTransparency(),
			'updated' => $this->extractDateTime( $object->getUpdated() ),
			'summary' => $object->getSummary(),
			'attendees' => $attendees,
			'startTime' => $this->extractDateTime( $object->getStartTime() ),
			'endTime' => $this->extractDateTime( $object->getEndTime() ),
			'calendarId' => $object->getCalendarId(),
			'allDay' => $object->getAllDay()
		];
	}

	public function hydrate( array $data, $object ) {
		/** @var GoogleEvent $object */
		$object->setDescription( $data['description'] ?? '' );
		$object->setLocation( $data['location'] ?? null );
		$object->setTransparency( $data['transparency'] ?? 'opaque' );
		$object->setSummary( $data['summary'] );
		$object->setAttendees( $data['attendees'] );
		$object->setStartTime( new \DateTime( $data['startTime'] ) );
		$object->setEndTime( new \DateTime( $data['endTime'] ) );
		$object->setAllDay( $data['allDay'] === true );

		return $object;
	}

	public function hydrateFromGoogleEvent ( \Google_Service_Calendar_Event $event, GoogleEvent $object ) : GoogleEvent {
		$object->setId( $event->getId() );
		$object->setDescription( $event->getDescription() );
		$object->setLocation( $event->getLocation() );
		$object->setTransparency( $event->getTransparency() );
		$object->setSummary( $event->getSummary() );
		$object->setAttendees( array_map( function ($attendee) {
			/** @var \Google_Service_Calendar_EventAttendee $attendee */
			return $attendee->getDisplayName();
		}, $event->getAttendees() ) );

		$allDay = $event->getStart()->getDateTime() == null;
		$object->setAllDay($allDay);
		
		if ($allDay) {
			$start = new \DateTime($event->getStart()->getDate());
			$start->setTime(0,0,0);
			$object->setStartTime($start);

			$end = new \DateTime($event->getEnd()->getDate());
			$end->setTime(23,59,59);
			$object->setEndTime($end);
		} else {
			$object->setStartTime(new \DateTime($event->getStart()->getDateTime()));
			$object->setEndTime(new \DateTime($event->getEnd()->getDateTime()));
		}

		$object->setUpdated( new \DateTime($event->getUpdated()) );
		$object->setEtag( trim($event->getEtag(), ' "\t\n\r') );

		return $object;
	}
}