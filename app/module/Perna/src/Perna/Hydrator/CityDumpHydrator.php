<?php

namespace Perna\Hydrator;

use Perna\Document\City;
use Perna\Document\Location;
use Zend\Hydrator\HydratorInterface;

/**
 * Hydrator which converts data from a OpenWeatherMap dump file to City objects and vice-versa
 *
 * @author      Jannik Portz
 * @package     Perna\Hydrator
 */
class CityDumpHydrator implements HydratorInterface {

	/** @inheritdoc */
	public function extract ( $object ) : array {
		/** @var City $object */
		return [
			'_id' => $object->getId(),
			'name' => $object->getName(),
			'country' => $object->getCountryCode(),
			'coord' => [
				'lon' => $object->getLocation()[0],
				'lat' => $object->getLocation()[1]
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
		$object->setLocation([$coord['lon'], $coord['lat']]);

		return $object;
	}
}