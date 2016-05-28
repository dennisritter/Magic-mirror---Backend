<?php

namespace Perna\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Document representing the current weather data
 *
 * @ODM\EmbeddedDocument
 *
 * @author      Jannik Portz
 * @package     Perna\Document\Weather
 */
class CurrentWeatherData extends TemporalWeatherData {

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
	 * @return \DateTime
	 */
	public function getSunset() {
		return $this->sunset;
	}

	/**
	 * @param \DateTime $sunset
	 */
	public function setSunset( $sunset ) {
		$this->sunset = $sunset;
	}
}