<?php

namespace Perna\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Swagger\Annotations as SWG;

/**
 * WeatherDataCache contains cached weather data for a specific weather location
 *
 * @SWG\Definition(
 *   @SWG\Xml(name="WeatherData")
 * )
 *
 * @ODM\EmbeddedDocument
 *
 * @author      Jannik Portz
 * @package     Perna\Document\Weather
 */
class WeatherDataCache {

	/**
	 * DateTime when the current weather data in the cache has been updated.
	 *
	 * @ODM\Field(type="date")
	 *
	 * @var       \DateTime
	 */
	protected $fetchedCurrent;

	/**
	 * DateTime when today's upcoming data in the cache has been updated.
	 *
	 * @ODM\Field(type="date")
	 *
	 * @var       \DateTime
	 */
	protected $fetchedToday;

	/**
	 * DateTime when the daily weather data in the cache has been updated.
	 *
	 * @ODM\Field(type="date")
	 *
	 * @var       \DateTime
	 */
	protected $fetchedDaily;

	/**
	 * The current weather data
	 *
	 * @SWG\Property(
	 *   @SWG\Schema(ref="CurrentWeatherData")
	 * )
	 * @ODM\EmbedOne(targetDocument="CurrentWeatherData")
	 *
	 * @var       CurrentWeatherData
	 */
	protected $current;

	/**
	 * Temporal data for current day.
	 * Supposed to be only future data points for today.
	 *
	 * @SWG\Property(
	 *   type="array",
	 *   @SWG\Items(ref="TemporalWeatherData")
	 * )
	 * @ODM\EmbedMany(targetDocument="TemporalWeatherData")
	 *
	 * @var       TemporalWeatherData[]
	 */
	protected $today;

	/**
	 * Daily weather data for the upcoming days
	 *
	 * @SWG\Property(
	 *   type="array",
	 *   @SWG\Items(ref="DailyWeatherData")
	 * )
	 * @ODM\EmbedMany(targetDocument="DailyWeatherData")
	 *
	 * @var       DailyWeatherData[]
	 */
	protected $daily;

	/**
	 * @return \DateTime
	 */
	public function getFetchedCurrent() {
		return $this->fetchedCurrent;
	}

	/**
	 * @param \DateTime $fetchedCurrent
	 */
	public function setFetchedCurrent( $fetchedCurrent ) {
		$this->fetchedCurrent = $fetchedCurrent;
	}

	/**
	 * @return \DateTime
	 */
	public function getFetchedToday() {
		return $this->fetchedToday;
	}

	/**
	 * @param \DateTime $fetchedToday
	 */
	public function setFetchedToday( $fetchedToday ) {
		$this->fetchedToday = $fetchedToday;
	}

	/**
	 * @return \DateTime
	 */
	public function getFetchedDaily() {
		return $this->fetchedDaily;
	}

	/**
	 * @param \DateTime $fetchedDaily
	 */
	public function setFetchedDaily( $fetchedDaily ) {
		$this->fetchedDaily = $fetchedDaily;
	}

	/**
	 * @return CurrentWeatherData
	 */
	public function getCurrent() {
		return $this->current;
	}

	/**
	 * @param CurrentWeatherData $current
	 */
	public function setCurrent( $current ) {
		$this->current = $current;
	}

	/**
	 * @return TemporalWeatherData[]
	 */
	public function getToday() {
		return $this->today;
	}

	/**
	 * @param TemporalWeatherData[] $today
	 */
	public function setToday( $today ) {
		$this->today = $today;
	}

	/**
	 * @return DailyWeatherData[]
	 */
	public function getDaily() {
		return $this->daily;
	}

	/**
	 * @param DailyWeatherData[] $daily
	 */
	public function setDaily( $daily ) {
		$this->daily = $daily;
	}
}