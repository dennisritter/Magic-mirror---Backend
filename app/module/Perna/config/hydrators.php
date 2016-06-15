<?php

use Perna\Document\GoogleAccessToken;
use Perna\Document\Station;
use Perna\Factory\DependencyTypes;
use Perna\Hydrator\AbstractModuleHydrator;
use Perna\Hydrator\AccessTokenHydrator;
use Perna\Hydrator\CalendarModuleHydrator;
use Perna\Hydrator\CityHydrator;
use Perna\Hydrator\DepartureHydrator;
use Perna\Hydrator\GoogleCalendarHydrator;
use Perna\Hydrator\GoogleEventHydrator;
use Perna\Hydrator\StationHydrator;
use Perna\Hydrator\UserHydrator;
use Perna\Hydrator\UserTokenHydrator;
use Perna\Hydrator\Weather\CurrentWeatherDataHydrator;
use Perna\Hydrator\Weather\DailyWeatherDataHydrator;
use Perna\Hydrator\Weather\TemporalWeatherDataHydrator;
use Perna\Hydrator\Weather\WeatherDataCacheHydrator;
use Perna\Hydrator\WeatherModuleHydrator;
use Perna\Factory\Factory;
use Perna\Service\PublicTransport\ProductsService;

return [
	'invokables' => [
		UserHydrator::class => UserHydrator::class,
		UserTokenHydrator::class => UserTokenHydrator::class,
		AccessTokenHydrator::class => AccessTokenHydrator::class,
		GoogleAccessToken::class => GoogleAccessToken::class,
		GoogleCalendarHydrator::class => GoogleCalendarHydrator::class,
		GoogleEventHydrator::class => GoogleEventHydrator::class,
		AbstractModuleHydrator::class => AbstractModuleHydrator::class,
		CalendarModuleHydrator::class => CalendarModuleHydrator::class,
		WeatherModuleHydrator::class => WeatherModuleHydrator::class,
		CityHydrator::class => CityHydrator::class,
		CurrentWeatherDataHydrator::class => CurrentWeatherDataHydrator::class,
		TemporalWeatherDataHydrator::class => TemporalWeatherDataHydrator::class,
		DailyWeatherDataHydrator::class => DailyWeatherDataHydrator::class,
		WeatherDataCacheHydrator::class => WeatherDataCacheHydrator::class
	],
	'factories' => [
		StationHydrator::class => new Factory( StationHydrator::class, [
			ProductsService::class => DependencyTypes::SERVICE
		]),
		DepartureHydrator::class => new Factory( DepartureHydrator::class, [
			ProductsService::class => DependencyTypes::SERVICE
		])
	]
];