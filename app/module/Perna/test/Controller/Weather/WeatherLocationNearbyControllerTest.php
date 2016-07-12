<?php

namespace Perna\Test\Controller\Weather;

use Perna\Controller\Weather\WeatherLocationNearbyController;
use Perna\Service\Weather\GeoNamesAccessService;
use Zend\Http\Request;
use Zend\Http\Response;

class WeatherLocationNearbyControllerTest extends AbstractWeatherLocationTestCase {

	const ENDPOINT = '/v1/weather/locations/nearby';
	const DUMMY_LAT = 13;
	const DUMMY_LNG = 50;

	/**
	 * GET with coordinates
	 * Returns list of two locations
	 */
	public function testGetSuccess () {
		$at = $this->getValidAccessToken();
		$this->setRequestHeaderLine('Access-Token', $at->getToken());

		$response = new Response();
		$response->setContent(json_encode([
			'geonames' => [
				$this->toApiCityData(self::DUMMY_CITY),
				$this->toApiCityData(self::DUMMY_CITY_2)
			]
		]));

		$this->httpClient
			->expects($this->once())
			->method('send')
			->with($this->callback(function ($request) {
				if (!$request instanceof Request)
					return false;

				$query = $request->getQuery();
				if ($query->get('lat') != self::DUMMY_LAT)
					return false;

				if ($query->get('lng') != self::DUMMY_LNG)
					return false;

				if ($request->getUriString() !== GeoNamesAccessService::ENDPOINT_NEARBY)
					return false;

				if (empty($query->get('username')))
					return false;

				return true;
			}))
			->willReturn($response);

		$this->documentManager->expects($this->never())->method('persist');

		/** @var Request $request */
		$request = $this->getRequest();
		$query = $request->getQuery();
		$query->set('latitude', self::DUMMY_LAT);
		$query->set('longitude', self::DUMMY_LNG);

		$this->dispatch(self::ENDPOINT, Request::METHOD_GET);
		$this->assertControllerIs(WeatherLocationNearbyController::class);
		$data = $this->getSuccessResponseData();
		$this->assertEquals(self::DUMMY_CITY, $data);
	}

	/**
	 * GET with coordinates
	 * No nearby location found
	 */
	public function testNotFound () {
		$at = $this->getValidAccessToken();
		$this->setRequestHeaderLine('Access-Token', $at->getToken());

		$response = new Response();
		$response->setContent(json_encode([
			'geonames' => []
		]));

		$this->httpClient
			->expects($this->once())
			->method('send')
			->willReturn($response);

		/** @var Request $request */
		$request = $this->getRequest();
		$query = $request->getQuery();
		$query->set('latitude', self::DUMMY_LAT);
		$query->set('longitude', self::DUMMY_LNG);

		$this->dispatch(self::ENDPOINT, Request::METHOD_GET);
		$this->getErrorResponseContent(404);
	}

	protected function abstractValidationTest (array $data) {
		$at = $this->getValidAccessToken();
		$this->setRequestHeaderLine('Access-Token', $at->getToken());

		$this->httpClient->expects($this->never())->method('send');

		/** @var Request $request */
		$request = $this->getRequest();
		$query = $request->getQuery();
		foreach ( $data as $key => $value ) {
			$query->set($key, $value);
		}

		$this->dispatch(self::ENDPOINT, Request::METHOD_GET);
		$this->getErrorResponseContent(422);
	}

	/**
	 * GET without coordinates
	 * Throws validation error
	 */
	public function testValidationErrorNoCoordinates () {
		$this->abstractValidationTest([]);
	}

	/**
	 * GET without latitude
	 * Throws validation error
	 */
	public function testValidationErrorNoLatitude () {
		$this->abstractValidationTest([
			'lng' => self::DUMMY_LNG
		]);
	}

	/**
	 * GET without longitude
	 * Throws validation error
	 */
	public function testValidationErrorNoLongitude () {
		$this->abstractValidationTest([
			'lat' => self::DUMMY_LAT
		]);
	}

	public function testAccessTokenRequired () {
		$this->abstractTestAccessTokenRequired(self::ENDPOINT, Request::METHOD_GET);
	}

	public function testMethodsNotAllowed () {
		$this->assertOtherMethodsNotAllowed(self::ENDPOINT, [Request::METHOD_GET]);
	}
}