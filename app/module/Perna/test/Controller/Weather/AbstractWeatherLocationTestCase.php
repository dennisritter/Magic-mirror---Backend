<?php

namespace Perna\Test\Controller\Weather;

use Perna\Document\City;
use Perna\Test\Controller\AbstractControllerTestCase;

class AbstractWeatherLocationTestCase extends AbstractControllerTestCase {

	const DUMMY_ID = 12345;

	const DUMMY_QUERY = 'test';

	const DUMMY_CITY = [
		'id' => self::DUMMY_ID,
		'name' => 'Castrop-Rauxel',
		'location' => [13,50],
		'countryCode' => 'DE'
	];

	const DUMMY_CITY_2 = [
		'id' => 54321,
		'name' => 'Buxtehude',
		'countryCode' => 'DE',
		'location' => [14,60]
	];

	protected function toApiCityData ( array $data ) {
		return [
			'geonameId' => $data['id'],
			'name' => $data['name'],
			'countryCode' => $data['countryCode'],
			'lat' => $data['location'][0],
			'lng' => $data['location'][1]
		];
	}

	public function getDummyCityObject () {
		$city = new City();
		$city->setId(self::DUMMY_ID);
		$city->setName(self::DUMMY_CITY['name']);
		$city->setLocation(self::DUMMY_CITY['location']);
		$city->setCountryCode(self::DUMMY_CITY['countryCode']);
		return $city;
	}
}