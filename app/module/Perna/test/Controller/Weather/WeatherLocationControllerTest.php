<?php

namespace Perna\Test\Controller\Weather;

use Perna\Controller\Weather\WeatherLocationController;
use Perna\Document\AccessToken;
use Perna\Document\City;
use Perna\Service\Weather\GeoNamesAccessService;
use Perna\Test\Controller\AbstractControllerTestCase;
use Zend\Http\Request;
use Zend\Http\Response;

class WeatherLocationControllerTest extends AbstractControllerTestCase {

	const DUMMY_ID = 12345;
	const ENDPOINT = '/v1/weather/locations/%s';
	const DUMMY_CITY = [
		'id' => self::DUMMY_ID,
		'name' => 'Castrop-Rauxel',
		'location' => [13,50],
		'countryCode' => 'DE'
	];

	protected function successAssertions () {
		$this->assertControllerIs( WeatherLocationController::class );
		$data = $this->getSuccessResponseData();

		foreach ( self::DUMMY_CITY as $key => $value ) {
			$this->assertArrayHasKey( $key, $data );
			$this->assertEquals( $value, $data[$key] );
		}
	}

	public function testGetSuccessInDb () {
		$at = $this->getValidAccessToken( false );
		$this->setRequestHeaderLine('Access-Token', $at->getToken());

		$cd = [
			'id' => self::DUMMY_ID,
			'name' => 'Castrop-Rauxel',
			'countryCode' => 'DE',
			'location' => [13,50]
		];

		$city = new City();
		$city->setId(self::DUMMY_ID);
		$city->setName($cd['name']);
		$city->setCountryCode($cd['countryCode']);
		$city->setLocation($cd['location']);

		$this->documentRepository
			->expects($this->exactly(2))
			->method('find')
			->withConsecutive(
				[$this->equalTo(self::DUMMY_ACCESS_TOKEN)],
				[$this->equalTo(self::DUMMY_ID)]
			)
			->willReturnMap([
				[self::DUMMY_ACCESS_TOKEN, 0, null, $at],
				[self::DUMMY_ID, 0, null, $city]
			]);

		$this->documentManager
			->expects($this->exactly(2))
			->method('getRepository')
			->withConsecutive(
				[$this->equalTo(AccessToken::class)],
				[$this->equalTo(City::class)]
			)
			->willReturn( $this->documentRepository );

		$this->httpClient->expects($this->never())->method('send');

		$this->dispatch(sprintf(self::ENDPOINT, self::DUMMY_ID), Request::METHOD_GET);
		$this->successAssertions();
	}

	public function testGetSuccessFromApi () {
		$at = $this->getValidAccessToken( false );
		$this->setRequestHeaderLine('Access-Token', $at->getToken());
		
		$this->documentRepository
			->expects($this->exactly(2))
			->method('find')
			->withConsecutive(
				[$this->equalTo(self::DUMMY_ACCESS_TOKEN)],
				[$this->equalTo(self::DUMMY_ID)]
			)
			->willReturnMap([
				[self::DUMMY_ACCESS_TOKEN, 0, null, $at],
				[self::DUMMY_ID, 0, null, null]
			]);

		$response = new Response();
		$response->setContent(json_encode([
			'geonameId' => self::DUMMY_ID,
			'countryCode' => self::DUMMY_CITY['countryCode'],
			'lat' => self::DUMMY_CITY['location'][0],
			'lng' => self::DUMMY_CITY['location'][1],
			'name' => self::DUMMY_CITY['name']
		]));
		
		$this->httpClient
			->expects($this->once())
			->method('send')
			->with($this->callback(function ($request) {
				if ( !$request instanceof Request )
					return false;

				$query = $request->getQuery();
				$id = $query->get('geonameId');
				if ( !is_int($id) || $id !== self::DUMMY_ID )
					return false;

				if ( $request->getUriString() !== GeoNamesAccessService::ENDPOINT_GET )
					return false;

				if ( empty( $query->get('username') ) )
					return false;

				return true;
			}))
			->willReturn( $response );

		$expectedCity = new City();
		$expectedCity->setId( self::DUMMY_ID );
		$expectedCity->setName( self::DUMMY_CITY['name'] );
		$expectedCity->setLocation( self::DUMMY_CITY['location'] );
		$expectedCity->setCountryCode( self::DUMMY_CITY['countryCode'] );

		$this->documentManager
			->expects($this->once())
			->method('persist')
			->with($this->equalTo($expectedCity));

		$this->documentManager
			->expects($this->once())
			->method('flush');

		$this->dispatch(sprintf(self::ENDPOINT, self::DUMMY_ID), Request::METHOD_GET);
		$this->successAssertions();
	}
}