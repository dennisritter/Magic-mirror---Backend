<?php

namespace Perna\Test\Controller\Weather;

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

	protected function toApiCityData ( array $data ) {
		return [
			'geonameId' => $data['id'],
			'name' => $data['name'],
			'countryCode' => $data['countryCode'],
			'lat' => $data['location'][0],
			'lng' => $data['location'][1]
		];
	}
}