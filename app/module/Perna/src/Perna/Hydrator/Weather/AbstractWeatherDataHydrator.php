<?php

namespace Perna\Hydrator\Weather;

use Perna\Document\AbstractWeatherData;
use Perna\Hydrator\AbstractHydrator;

abstract class AbstractWeatherDataHydrator extends AbstractHydrator {

	/** @inheritdoc */
	public function extract( $object ) {
		/** @var AbstractWeatherData $object */
		return [
			'weatherId' => $object->getWeatherId(),
			'windSpeed' => $object->getWindSpeed(),
			'cloudiness' => $object->getCloudiness(),
			'dateTime' => $this->extractDateTime( $object->getDateTime() )
		];
	}
	
	/** @inheritdoc */
	public function hydrate( array $data, $object ) {
		/** @var AbstractWeatherData $object */
		$object->setWeatherId( $data['weather']['id'] );
		$object->setWindSpeed( $data['wind']['speed'] );
		$object->setCloudiness( $data['clouds']['all'] );
		$object->setDateTime( new \DateTime( $data['dt'] ) );
		return $object;
	}
}