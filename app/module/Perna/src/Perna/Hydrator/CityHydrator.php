<?php

namespace Perna\Hydrator;

use Perna\Document\City;

class CityHydrator extends AbstractHydrator {

	/** @inheritdoc */
	public function extract ( $object ) {
		/** @var City $object */
		return [
			'id' => $object->getId(),
			'name' => $object->getName(),
			'countryCode' => $object->getCountryCode(),
			'location' => $object->getLocation()
		];
	}

	/** @inheritdoc */
	public function hydrate ( array $data, $object ) {
		/** @var City $object */
		$object->setName( $data['name'] );
		$object->setCountryCode( $data['countryCode'] );
		$object->setLocation( $data['location'] );
	}

	public function hydrateFromGeoNameResult ( array $data, City $city ) : City {
		$city->setId( $data['geonameId'] );
		$city->setCountryCode( $data['countryCode'] );
		$city->setLocation( [$data['lat'], $data['lng']] );
		$city->setName( $data['name'] );
		return $city;
	}
}