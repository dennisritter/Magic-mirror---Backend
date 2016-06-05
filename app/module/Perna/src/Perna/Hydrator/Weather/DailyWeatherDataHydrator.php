<?php

namespace Perna\Hydrator\Weather;

use Perna\Document\DailyWeatherData;

class DailyWeatherDataHydrator extends AbstractWeatherDataHydrator {

	/** @inheritdoc */
	public function extract ( $object ) {
		/** @var DailyWeatherData $object */
		return array_merge(parent::extract( $object ), [
			'temperature' => $object->getTemperature()
		]);
	}

	/** @inheritdoc */
	public function hydrate( array $data, $object ) {
		/** @var DailyWeatherData $object */
		parent::hydrate( $data, $object );
		$object->setTemperature([
			'average' => (float) $data['temp']['day'],
			'min' => (float) $data['temp']['min'],
			'max' => (float) $data['temp']['max']
		]);
		return $object;
	}

}