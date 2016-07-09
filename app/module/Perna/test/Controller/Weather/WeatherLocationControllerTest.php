<?php

namespace Perna\Test\Controller\Weather;

use Perna\Controller\Weather\WeatherLocationController;
use Perna\Document\AccessToken;
use Perna\Document\City;
use Perna\Test\Controller\AbstractControllerTestCase;
use Zend\Http\Request;

class WeatherLocationControllerTest extends AbstractControllerTestCase {

	const DUMMY_ID = 12345;
	const ENDPOINT = '/v1/weather/locations/%s';

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
			->expects($this->any())
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
		$this->assertControllerIs( WeatherLocationController::class );
		$data = $this->getSuccessResponseData();

		foreach ( $cd as $key => $value ) {
			$this->assertArrayHasKey( $key, $data );
			$this->assertEquals( $value, $data[$key] );
		}
	}
}