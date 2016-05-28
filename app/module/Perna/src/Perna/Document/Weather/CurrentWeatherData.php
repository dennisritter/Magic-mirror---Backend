<?php

namespace Perna\Document\Weather;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class CurrentWeatherData
 *
 * @ODM\EmbeddedDocument
 *
 * @author      Jannik Portz
 * @package     Perna\Document\Weather
 */
class CurrentWeatherData extends AbstractWeatherData {

	/**
	 * Current temperature in Kelvin
	 *
	 * @ODM\Field()
	 *
	 * @var       float
	 */
	protected $temperature;

	/**
	 * Time of sunrise on current day
	 *
	 * @ODM\Field(type="date")
	 *
	 * @var       \DateTime
	 */
	protected $sunrise;

	/**
	 * Time of sunset on current day
	 *
	 * @ODM\Field(type="date")
	 *
	 * @var
	 */
	protected $sunset;

	/**
	 * @return float
	 */
	public function getTemperature() {
		return $this->temperature;
	}

	/**
	 * @param float $temperature
	 */
	public function setTemperature( $temperature ) {
		$this->temperature = $temperature;
	}

	/**
	 * @return \DateTime
	 */
	public function getSunrise() {
		return $this->sunrise;
	}

	/**
	 * @param \DateTime $sunrise
	 */
	public function setSunrise( $sunrise ) {
		$this->sunrise = $sunrise;
	}

	/**
	 * @return mixed
	 */
	public function getSunset() {
		return $this->sunset;
	}

	/**
	 * @param mixed $sunset
	 */
	public function setSunset( $sunset ) {
		$this->sunset = $sunset;
	}
}