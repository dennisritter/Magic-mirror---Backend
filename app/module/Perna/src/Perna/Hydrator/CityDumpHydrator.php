<?php

namespace Perna\Hydrator;

use Perna\Document\City;
use Perna\Document\Location;

/**
 * Hydrator which converts data from a OpenWeatherMap dump file to City objects and vice-versa
 *
 * @author      Jannik Portz
 * @package     Perna\Hydrator
 */
class CityDumpHydrator extends AbstractHydrator {

	/** @inheritdoc */
	public function extract ( $object ) : array {
		/** @var City $object */
		return [
			'_id' => $object->getId(),
			'name' => $object->getName(),
			'country' => $object->getCountryCode(),
			'coord' => [
				'lat' => $object->getLocation()[0],
				'lon' => $object->getLocation()[1]
			]
		];
	}

	/** @inheritdoc */
	public function hydrate ( array $data, $object ) : City {
		/** @var City $object */
		$object->setId( $data['_id'] );
		$object->setName( $data['name'] );
		$object->setCountryCode( $data['country'] );
		$coord = $data['coord'];
		$object->setLocation([$coord['lat'], $coord['lon']]);

		return $object;
	}
}