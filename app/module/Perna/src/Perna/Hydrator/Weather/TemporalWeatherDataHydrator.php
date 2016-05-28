<?php

namespace Perna\Hydrator\Weather;

use Perna\Document\TemporalWeatherData;

class TemporalWeatherDataHydrator extends AbstractWeatherDataHydrator {

	/** @inheritdoc */
	public function extract( $object ) {
		/** @var TemporalWeatherData $object */
		return array_merge( parent::extract( $object ), [
			'temperature' => $object->getTemperature()
		]);
	}

	/** @inheritdoc */
	public function hydrate ( array $data, $object ) {
		/** @var TemporalWeatherData $object */
		parent::hydrate( $data, $object );
		$object->setTemperature( $data['main']['temp'] );
		return $object;
	}
}