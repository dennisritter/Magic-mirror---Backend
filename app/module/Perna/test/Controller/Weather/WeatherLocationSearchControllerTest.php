<?php

namespace Perna\Test\Controller\Weather;

use Perna\Controller\Weather\WeatherLocationSearchController;
use Perna\Service\Weather\GeoNamesAccessService;
use Zend\Http\Request;
use Zend\Http\Response;

class WeatherLocationSearchControllerTest extends AbstractWeatherLocationTestCase {

	const ENDPOINT = '/v1/weather/locations/search';

	/**
	 * GET with search query
	 * Returns two results from API
	 */
	public function testGetSuccess () {
		$at = $this->getValidAccessToken();
		$this->setRequestHeaderLine('Access-Token', $at->getToken());

		$dummy2 = [
			'id' => 54321,
			'name' => 'Buxtehude',
			'countryCode' => 'DE',
			'location' => [14,60]
		];

		$response = new Response();
		$response->setContent(json_encode([
			'geonames' => [
				$this->toApiCityData(self::DUMMY_CITY),
				$this->toApiCityData($dummy2)
			]
		]));

		$this->httpClient
			->expects($this->once())
			->method('send')
			->with($this->callback(function ($request) {
				if (!$request instanceof Request)
					return false;

				$query = $request->getQuery();
				if ($query->get('q') !== self::DUMMY_QUERY)
					return false;

				if ($request->getUriString() !== GeoNamesAccessService::ENDPOINT_SEARCH)
					return false;

				if (empty($query->get('username')))
					return false;

				return true;
			}))
			->willReturn($response);

		$this->documentManager->expects($this->never())->method('persist');

		/** @var Request $request */
		$request = $this->getRequest();
		$request->getQuery()->set('query', self::DUMMY_QUERY);

		$this->dispatch(self::ENDPOINT, Request::METHOD_GET);
		$this->assertControllerIs(WeatherLocationSearchController::class);
		$data = $this->getSuccessResponseData();

		$this->assertCount(2, $data);
		$this->assertEquals([self::DUMMY_CITY, $dummy2], $data);
	}

	/**
	 * GET without search query
	 * Throws validation error
	 */
	public function testValidationErrorNoQuery () {
		$at = $this->getValidAccessToken();
		$this->setRequestHeaderLine('Access-Token', $at->getToken());

		$this->httpClient->expects($this->never())->method('send');
		
		$this->dispatch(self::ENDPOINT, Request::METHOD_GET);
		$this->getErrorResponseContent(422);
	}

	public function testAccessTokenRequired () {
		$this->abstractTestAccessTokenRequired(self::ENDPOINT, Request::METHOD_GET);
	}

	public function testMethodsNotAllowed () {
		$this->assertOtherMethodsNotAllowed(self::ENDPOINT, [Request::METHOD_GET]);
	}
}