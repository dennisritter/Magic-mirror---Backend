<?php

namespace Perna\Hydrator\Weather;

use Perna\Document\WeatherDataCache;
use Perna\Hydrator\AbstractHydrator;

class WeatherDataCacheHydrator extends AbstractHydrator {

	public function extract( $object ) {
		/** @var WeatherDataCache $object */
		$current = new CurrentWeatherDataHydrator();
		$temporal = new TemporalWeatherDataHydrator();
		$daily = new DailyWeatherDataHydrator();
		
		return [
			'current' => $current->extract( $object->getCurrent() ),
			'today' => $temporal->extractMany( $object->getToday() ),
			'daily' => $daily->extractMany( $object->getDaily() )
		];
	}

	public function hydrate( array $data, $object ) {
		return $object;
	}
}