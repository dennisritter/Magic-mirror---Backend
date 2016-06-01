<?php

namespace Perna\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Swagger\Annotations as SWG;

/**
 * Document representing the weather data for a whole day
 *
 * @SWG\Definition(
 *   @SWG\Xml(name="DailyWeatherData")
 * )
 *
 * @ODM\EmbeddedDocument
 *
 * @author      Jannik Portz
 * @package     Perna\Document\Weather
 */
class DailyWeatherData extends AbstractWeatherData {

	/**
	 * Key-Value-Pairs containing information on the average, min and max temperatures on the specific day.
	 * Temperatures are specified in Kelvin.
	 *
	 * @SWG\Property(
	 *   property="temperature",
	 *   ref="#/definitions/DayTemperatures"
	 * )
	 *
	 * @ODM\Field(type="hash")
	 *
	 * @var       array
	 */
	protected $temperature;

	/**
	 * @return array
	 */
	public function getTemperature() {
		return $this->temperature;
	}

	/**
	 * @param array $temperature
	 */
	public function setTemperature( $temperature ) {
		$this->temperature = $temperature;
	}
	
}