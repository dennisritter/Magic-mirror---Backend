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
				'lon' => $object->getLocation()->getLongitude(),
				'lat' => $object->getLocation()->getLatitude()
			]
		];
	}

	/** @inheritdoc */
	public function hydrate ( array $data, $object ) : City {
		/** @var City $object */
		$object->setId( $data['_id'] );
		$object->setName( $data['name'] );
		$object->setCountryCode( $data['country'] );
		$location = new Location();
		$coord = $data['coord'];
		$location->setLatitude( $coord['lat'] );
		$location->setLongitude( $coord['lon'] );
		$object->setLocation( $location );

		return $object;
	}
}