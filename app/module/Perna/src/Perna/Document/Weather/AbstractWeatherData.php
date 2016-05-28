<?php

namespace Perna\Document\Weather;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Abstraction for Weather Data Documents
 *
 * @ODM\MappedSuperclass
 *
 * @author      Jannik Portz
 * @package     Perna\Document\Weather
 */
abstract class AbstractWeatherData {

	/**
	 * The OpenWeatherMap weather state id.
	 * More Information on http://openweathermap.org/weather-conditions
	 *
	 * @ODM\Field()
	 *
	 * @var       int
	 */
	protected $weatherId;

	/**
	 * Wind speed in m/s
	 *
	 * @ODM\Field()
	 *
	 * @var       float
	 */
	protected $windSpeed;

	/**
	 * Cloudiness in percent
	 *
	 * @ODM\Field()
	 *
	 * @var       float
	 */
	protected $cloudiness;

	/**
	 * The date and time of this forecast data
	 *
	 * @ODM\Field(type="date")
	 *
	 * @var       \DateTime
	 */
	protected $dateTime;

	/**
	 * @return int
	 */
	public function getWeatherId() {
		return $this->weatherId;
	}

	/**
	 * @param int $weatherId
	 */
	public function setWeatherId( $weatherId ) {
		$this->weatherId = $weatherId;
	}

	/**
	 * @return float
	 */
	public function getWindSpeed() {
		return $this->windSpeed;
	}

	/**
	 * @param float $windSpeed
	 */
	public function setWindSpeed( $windSpeed ) {
		$this->windSpeed = $windSpeed;
	}

	/**
	 * @return float
	 */
	public function getCloudiness() {
		return $this->cloudiness;
	}

	/**
	 * @param float $cloudiness
	 */
	public function setCloudiness( $cloudiness ) {
		$this->cloudiness = $cloudiness;
	}

	/**
	 * @return \DateTime
	 */
	public function getDateTime() {
		return $this->dateTime;
	}

	/**
	 * @param \DateTime $dateTime
	 */
	public function setDateTime( $dateTime ) {
		$this->dateTime = $dateTime;
	}
}