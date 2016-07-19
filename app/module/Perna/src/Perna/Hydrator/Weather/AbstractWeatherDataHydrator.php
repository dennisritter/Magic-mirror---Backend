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
		$object->setWeatherId( (int) $data['weather'][0]['id'] );
		$object->setWindSpeed( (float) ($data['wind']['speed'] ?? $data['speed']) );
		$object->setCloudiness( (float) ($data['clouds']['all'] ?? $data['clouds']) );
		$object->setDateTime( $this->createDate( $data['dt'] ) );
		return $object;
	}
}