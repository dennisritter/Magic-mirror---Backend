<?php

namespace Perna\Document\Weather;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Temporal Weather Data describes the weather for one specific point of time
 *
 * @ODM\EmbeddedDocument()
 *
 * @author      Jannik Portz
 * @package     Perna\Document\Weather
 */
class TemporalWeatherData extends AbstractWeatherData {

	/**
	 * The temperature for the specified point of time in Kelvin.
	 *
	 * @ODM\Field()
	 *
	 * @var       float
	 */
	protected $temperature;

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
}