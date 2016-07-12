<?php

namespace Perna\Test\Controller\Weather;

use Perna\Controller\Weather\WeatherDataController;
use Perna\Document\CurrentWeatherData;
use Perna\Document\DailyWeatherData;
use Perna\Document\TemporalWeatherData;
use Perna\Document\WeatherDataCache;
use Zend\Http\Request;

class WeatherDataControllerTest extends AbstractWeatherLocationTestCase {

	const ENDPOINT = '/v1/weather/%s';

	public function testGetSuccessFromApi () {
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
			->willReturnMap([
				[$at->getToken(), 0, null, $at],
				[self::DUMMY_ID, 0, null, $city]
			]);

		$this->documentManager
			->expects($this->never())
			->method('flush');

		$this->dispatch(sprintf(self::ENDPOINT, self::DUMMY_ID), Request::METHOD_GET);
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
		$temporal1->setDateTime(new \DateTime('13:00'));

		$temporal2 = new TemporalWeatherData();
		$temporal2->setWeatherId(802);
		$temporal2->setWindSpeed(2.2);
		$temporal2->setCloudiness(31);
		$temporal2->setTemperature(288);
		$temporal2->setDateTime(new \DateTime('17:00'));
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
}