<?php

namespace Perna\Filter;


use Perna\Document\GoogleEvent;
use Zend\Filter\Exception;
use Zend\Filter\FilterInterface;

/**
 * Filter for today's upcoming events
 *
 * @author      Jannik Portz
 * @package     Perna\Filter
 */
class UpcomingTodayEventsFilter implements FilterInterface {

	public function filter ( $value ) {
		$events = [];
		$now = new \DateTime('now');
		$tomorrow = clone $now;
		$tomorrow->add( new \DateInterval('P1D') );
		$tomorrow->setTime(0,0,0);

		foreach ( $value as $event ) {
			/** @var GoogleEvent $event */
			if ( $event->getEndTime() >= $now && $event->getStartTime() < $tomorrow ) {
				$events[] = $event;
			}
		}

		usort( $events, function ( GoogleEvent $a, GoogleEvent $b ) : int {
			return $a->getStartTime() <=> $b->getStartTime();
		} );

		return $events;
	}
}