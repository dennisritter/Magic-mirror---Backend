<?php

namespace Perna\Hydrator\Weather;

use Perna\Document\CurrentWeatherData;

class CurrentWeatherDataHydrator extends TemporalWeatherDataHydrator {

	/** @inheritdoc */
	public function extract( $object ) {
		/** @var CurrentWeatherData $object */
		return array_merge( parent::extract( $object ), [
			'sunrise' => $this->extractDateTime( $object->getSunrise() ),
			'sunset' => $this->extractDateTime( $object->getSunset() )
		]);
	}

	/** @inheritdoc */
	public function hydrate( array $data, $object ) {
		/** @var CurrentWeatherData $object */
		parent::hydrate( $data, $object );
		$object->setSunrise( $this->createDate( $data['sys']['sunrise'] ) );
		$object->setSunset( $this->createDate( $data['sys']['sunset'] ) );
		return $object;
	}
}