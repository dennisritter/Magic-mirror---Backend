<?php

use Perna\Document\GoogleAccessToken;
use Perna\Hydrator\AccessTokenHydrator;
use Perna\Hydrator\CityDumpHydrator;
use Perna\Hydrator\CityHydrator;
use Perna\Hydrator\GoogleCalendarHydrator;
use Perna\Hydrator\GoogleEventHydrator;
use Perna\Hydrator\UserHydrator;
use Perna\Hydrator\UserTokenHydrator;
use Perna\Hydrator\Weather\CurrentWeatherDataHydrator;
use Perna\Hydrator\Weather\DailyWeatherDataHydrator;
use Perna\Hydrator\Weather\TemporalWeatherDataHydrator;
use Perna\Hydrator\Weather\WeatherDataCacheHydrator;

return [
	'invokables' => [
		CityDumpHydrator::class => CityDumpHydrator::class,
		UserHydrator::class => UserHydrator::class,
		UserTokenHydrator::class => UserTokenHydrator::class,
		AccessTokenHydrator::class => AccessTokenHydrator::class,
		GoogleAccessToken::class => GoogleAccessToken::class,
		GoogleCalendarHydrator::class => GoogleCalendarHydrator::class,
		GoogleEventHydrator::class => GoogleEventHydrator::class,
		CityHydrator::class => CityHydrator::class,
		CurrentWeatherDataHydrator::class => CurrentWeatherDataHydrator::class,
		TemporalWeatherDataHydrator::class => TemporalWeatherDataHydrator::class,
		DailyWeatherDataHydrator::class => DailyWeatherDataHydrator::class,
		WeatherDataCacheHydrator::class => WeatherDataCacheHydrator::class
	]
];