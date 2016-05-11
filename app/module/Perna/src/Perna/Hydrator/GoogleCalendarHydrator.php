<?php

namespace Perna\Hydrator;

use Perna\Document\GoogleCalendar;

class GoogleCalendarHydrator extends AbstractHydrator {

	/** @inheritdoc */
	public function extract( $object ) {
		/** @var GoogleCalendar $object */
		return [
			'id' => $object->getId(),
			'accessRole' => $object->getAccessRole(),
			'color' => $object->getColor(),
			'summary' => $object->getSummary(),
			'description' => $object->getDescription(),
			'selected' => $object->getSelected(),
			'primary' => $object->getPrimary()
		];
	}

	/** @inheritdoc */
	public function hydrate( array $data, $object ) {
		/** @var GoogleCalendar $object */
		$object->setId( $data['id'] );
		$object->setAccessRole( $data['accessRole'] );
		$object->setColor( $data['backgroundColor'] );
		$object->setSummary( $data['summary'] );
		$object->setDescription( $data['description'] ?? '' );
		$object->setSelected( $data['selected'] ?? false );
		$object->setPrimary( $data['primary'] ?? false );

		return $object;
	}
}