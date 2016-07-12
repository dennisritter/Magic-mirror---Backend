<?php

namespace Perna\Test\Controller\Weather;

use Perna\Controller\Weather\WeatherDataController;
use Perna\Document\CurrentWeatherData;
use Perna\Document\DailyWeatherData;
use Perna\Document\TemporalWeatherData;
use Perna\Document\WeatherDataCache;
use Perna\Service\Weather\WeatherDataAccessService;
use Zend\Http\Request;
use Zend\Http\Response;

class WeatherDataControllerTest extends AbstractWeatherLocationTestCase {

	const ENDPOINT = '/v1/weather/%s';

	/**
	 * GET with location id
	 * Serves all data from cache
	 */
	public function testGetSuccessFromCache () {
		$at = $this->getValidAccessToken(false);
		$this->setRequestHeaderLine('Access-Token', $at->getToken());

		$city = $this->getDummyCityObject();
		$cache = $this->createDummyCache();
		$now = new \DateTime('now');
		$cache->setFetchedToday($now);
		$cache->setFetchedCurrent($now);
		$cache->setFetchedDaily($now);
		$city->setWeatherDataCache($cache);
		
		$this->documentRepository
			->expects($this->any())
			->method('find')
			->withConsecutive(
				[$this->equalTo($at->getToken())],
				[$this->equalTo(self::DUMMY_ID)]
			)
			->willReturnOnConsecutiveCalls($at, $city);

		$this->documentManager
			->expects($this->never())
			->method('flush');

		$this->dispatch(sprintf(self::ENDPOINT, self::DUMMY_ID), Request::METHOD_GET);
		$this->weatherDataResponseAssertions($cache);
	}

	/**
	 * GET with location id
	 * Refreshes all data from API
	 */
	public function testGetSuccessFromApi () {
		$at = $this->getValidAccessToken(false);
		$this->setRequestHeaderLine('Access-Token', $at->getToken());
		
		$city = $this->getDummyCityObject();

		// Note that the cache is not assigned to the city
		$cache = $this->createDummyCache();
		$cache->setFetchedCurrent((new \DateTime('now'))->add(new \DateInterval('PT15M1S')));
		$cache->setFetchedToday((new \DateTime('now'))->add(new \DateInterval('PT30M1S')));
		$cache->setFetchedDaily((new \DateTime('now'))->add(new \DateInterval('PT2H1S')));

		$this->documentRepository
			->expects($this->any())
			->method('find')
			->withConsecutive(
				[$this->equalTo($at->getToken())],
				[$this->equalTo(self::DUMMY_ID)]
			)
			->willReturnOnConsecutiveCalls($at, $city);

		$this->documentManager
			->expects($this->once())
			->method('flush');
		
		$requestValidatorFactory = function ( $endpoint ) use ( $city ) {
			return function ( $request ) use ( $endpoint, $city ) {
				if (!$request instanceof Request)
					return false;

				if ($request->getMethod() !== Request::METHOD_GET)
					return false;

				$query = $request->getQuery();
				$location = $city->getLocation();
				if (empty($query->get('appid')))
					return false;

				if ($query->get('lat') !== $location[0] || $query->get('lon') !== $location[1])
					return false;

				if ($request->getUriString() !== $endpoint)
					return false;

				return true;
			};
		};

		$current = $cache->getCurrent();
		$responseCurrent = new Response();
		$responseCurrent->setStatusCode(200);
		$responseCurrent->setContent(json_encode([
			'weather' => [[
				'id' => $current->getWeatherId(),
			]],
			'wind' => [
				'speed' => $current->getWindSpeed()
			],
			'clouds' => [
				'all' => $current->getCloudiness()
			],
			'dt' => $current->getDateTime()->format('U'),
			'main' => [
				'temp' => $current->getTemperature()
			],
			'sys' => [
				'sunrise' => $current->getSunrise()->format('U'),
				'sunset' => $current->getSunset()->format('U')
			]
		]));

		$this->httpClient
			->expects($this->at(0))
			->method('send')
			->with($this->callback($requestValidatorFactory(WeatherDataAccessService::ENDPOINT_CURRENT_WEATHER_DATA)))
			->willReturn($responseCurrent);

		$today = $cache->getToday();
		$list = [];
		foreach ( $today as $temporal ) {
			$list[] = [
				'weather' => [[
					'id' => $temporal->getWeatherId(),
				]],
				'wind' => [
					'speed' => $temporal->getWindSpeed()
				],
				'clouds' => [
					'all' => $temporal->getCloudiness()
				],
				'dt' => $temporal->getDateTime()->format('U'),
				'main' => [
					'temp' => $temporal->getTemperature()
				]
			];
		}
		
		$responseToday = new Response();
		$responseToday->setStatusCode(200);
		$responseToday->setContent(json_encode([
			'list' => $list
		]));

		$this->httpClient
			->expects($this->at(1))
			->method('send')
			->with($this->callback($requestValidatorFactory(WeatherDataAccessService::ENDPOINT_FORECAST)))
			->willReturn($responseToday);
		
		$days = $cache->getDaily();
		$list = [];
		foreach ( $days as $daily ) {
			$list[] = [
				'weather' => [[
					'id' => $daily->getWeatherId(),
				]],
				'wind' => [
					'speed' => $daily->getWindSpeed()
				],
				'clouds' => [
					'all' => $daily->getCloudiness()
				],
				'dt' => $daily->getDateTime()->format('U'),
				'temp' => [
					'day' => $daily->getTemperature()['average'],
					'min' => $daily->getTemperature()['min'],
					'max' => $daily->getTemperature()['max']
				]
			];
		}

		$responseDaily = new Response();
		$responseDaily->setStatusCode(200);
		$responseDaily->setContent(json_encode([
			'list' => $list
		]));

		$this->httpClient
			->expects($this->at(2))
			->method('send')
			->with($this->callback($requestValidatorFactory(WeatherDataAccessService::ENDPOINT_FORECAST_DAILY)))
			->willReturn($responseDaily);

		$this->dispatch(sprintf(self::ENDPOINT, self::DUMMY_ID), Request::METHOD_GET);
		$this->weatherDataResponseAssertions($cache);
	}

	/**
	 * Makes assertions that the response data matches the data in $cache
	 * @param     WeatherDataCache    $cache    Cache containing the expected data
	 */
	private function weatherDataResponseAssertions ( WeatherDataCache $cache ) {
		$this->assertControllerIs(WeatherDataController::class);
		$data = $this->getSuccessResponseData();
		$this->assertArrayHasKey('current', $data);
		$this->assertArrayHasKey('today', $data);
		$this->assertArrayHasKey('daily', $data);

		$current = $cache->getCurrent();
		$format = \DateTime::RFC3339;
		$this->assertEquals([
			'weatherId' => $current->getWeatherId(),
			'windSpeed' => $current->getWindSpeed(),
			'cloudiness' => $current->getCloudiness(),
			'temperature' => $current->getTemperature(),
			'sunrise' => $current->getSunrise()->format($format),
			'sunset' => $current->getSunset()->format($format),
			'dateTime' => $current->getDateTime()->format($format)
		], $data['current']);

		$expectedToday = [];
		foreach ( $cache->getToday() as $today ) {
			$expectedToday[] = [
				'weatherId' => $today->getWeatherId(),
				'windSpeed' => $today->getWindSpeed(),
				'cloudiness' => $today->getCloudiness(),
				'temperature' => $today->getTemperature(),
				'dateTime' => $today->getDateTime()->format($format)
			];
		}
		$this->assertEquals($expectedToday, $data['today']);

		$expectedDaily = [];
		foreach ( $cache->getDaily() as $daily ) {
			$expectedDaily[] = [
				'weatherId' => $daily->getWeatherId(),
				'windSpeed' => $daily->getWindSpeed(),
				'cloudiness' => $daily->getCloudiness(),
				'temperature' => $daily->getTemperature(),
				'dateTime' => $daily->getDateTime()->format($format)
			];
		}
		$this->assertEquals($expectedDaily, $data['daily']);
	}

	/**
	 * Creates a WeatherDataCache with dummy data
	 * @return    WeatherDataCache  The cache with dummy data
	 */
	protected function createDummyCache () : WeatherDataCache {
		$cache = new WeatherDataCache();
		$current = new CurrentWeatherData();
		$current->setWeatherId(800);
		$current->setWindSpeed(3.2);
		$current->setCloudiness(20);
		$current->setTemperature(290);
		$current->setSunrise(new \DateTime('07:00'));
		$current->setSunset(new \DateTime('22:00'));
		$current->setDateTime(new \DateTime('now'));
		$cache->setCurrent( $current );

		$temporal1 = new TemporalWeatherData();
		$temporal1->setWeatherId(801);
		$temporal1->setWindSpeed(2.1);
		$temporal1->setCloudiness(30);
		$temporal1->setTemperature(287);
		$temporal1->setDateTime((new \DateTime('now'))->add(new \DateInterval('PT1H')));

		$temporal2 = new TemporalWeatherData();
		$temporal2->setWeatherId(802);
		$temporal2->setWindSpeed(2.2);
		$temporal2->setCloudiness(31);
		$temporal2->setTemperature(288);
		$temporal2->setDateTime((new \DateTime('now'))->add(new \DateInterval('PT4H')));
		$cache->setToday([$temporal1, $temporal2]);

		$daily1 = new DailyWeatherData();
		$daily1->setWeatherId(803);
		$daily1->setWindSpeed(3.5);
		$daily1->setCloudiness(50);
		$daily1->setTemperature([
			'min' => 280,
			'average' => 290,
			'max' => 300
		]);
		$daily1->setDateTime(new \DateTime('2016-02-05'));

		$daily2 = new DailyWeatherData();
		$daily2->setWeatherId(804);
		$daily2->setWindSpeed(3.6);
		$daily2->setCloudiness(51);
		$daily2->setTemperature([
			'min' => 310,
			'average' => 320,
			'max' => 330
		]);
		$daily2->setDateTime(new \DateTime('2016-02-06'));
		$cache->setDaily([$daily1, $daily2]);
		return $cache;
	}

	public function testOtherMethodsNotAllowed () {
		$this->assertOtherMethodsNotAllowed(sprintf(self::ENDPOINT, self::DUMMY_ID), [Request::METHOD_GET]);
	}

	public function testAccessTokenRequired () {
		$this->abstractTestAccessTokenRequired(sprintf(self::ENDPOINT, self::DUMMY_ID), Request::METHOD_GET);
	}
}