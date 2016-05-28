<?php

use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Document\DailyWeatherData;
use Perna\Document\TemporalWeatherData;
use Perna\Factory\DependencyTypes;
use Perna\Factory\Factory;
use Perna\Hydrator\CityDumpHydrator;
use Perna\Hydrator\GoogleAccessTokenHydrator;
use Perna\Hydrator\GoogleCalendarHydrator;
use Perna\Hydrator\GoogleEventHydrator;
use Perna\Hydrator\Weather\CurrentWeatherDataHydrator;
use Perna\InputFilter\CityDumpInputFilter;
use Perna\Service\AuthenticationService;
use Perna\Service\CityImportService;
use Perna\Service\Factory\GoogleCalendarServiceFactory;
use Perna\Service\GoogleAuthenticationService;
use Perna\Service\GoogleCalendarEventsService;
use Perna\Service\GoogleCalendarService;
use Perna\Service\GUIDGenerator;
use Perna\Service\PasswordService;
use Perna\Service\UserService;
use Perna\Service\Weather\WeatherDataAccessService;
use Perna\Service\Weather\WeatherDataService;
use Perna\Service\WeatherLocationService;
use Zend\Di\ServiceLocator;

return [
	'aliases' => [
		DocumentManager::class => 'doctrine.documentmanager.odm_default'
	],
	'factories' => [
		CityImportService::class => new Factory(CityImportService::class, [
			DocumentManager::class => DependencyTypes::SERVICE,
			CityDumpHydrator::class => DependencyTypes::HYDRATOR,
			CityDumpInputFilter::class => DependencyTypes::INPUT_FILTER
		]),
		UserService::class => new Factory(UserService::class, [
			PasswordService::class => DependencyTypes::SERVICE,
			DocumentManager::class => DependencyTypes::SERVICE
		]),
		AuthenticationService::class => new Factory(AuthenticationService::class, [
			DocumentManager::class => DependencyTypes::SERVICE,
			GUIDGenerator::class => DependencyTypes::SERVICE,
			PasswordService::class => DependencyTypes::SERVICE
		]),
		GoogleAuthenticationService::class => new Factory(GoogleAuthenticationService::class, [
			GoogleAccessTokenHydrator::class => DependencyTypes::HYDRATOR,
			DocumentManager::class => DependencyTypes::SERVICE,
			GUIDGenerator::class => DependencyTypes::SERVICE
		]),
		GoogleCalendarService::class => new Factory(GoogleCalendarService::class, [
			GoogleAuthenticationService::class => DependencyTypes::SERVICE,
			GoogleCalendarHydrator::class => DependencyTypes::HYDRATOR,
			GoogleEventHydrator::class => DependencyTypes::HYDRATOR,
			GoogleCalendarEventsService::class => DependencyTypes::SERVICE,
			DocumentManager::class => DependencyTypes::SERVICE
		]),
		GoogleCalendarEventsService::class => new Factory(GoogleCalendarEventsService::class, [
			GoogleEventHydrator::class => DependencyTypes::HYDRATOR,
			GUIDGenerator::class => DependencyTypes::SERVICE,
			DocumentManager::class => DependencyTypes::SERVICE
		]),
		WeatherLocationService::class => new Factory(WeatherLocationService::class, [
			DocumentManager::class => DependencyTypes::SERVICE
		]),
		WeatherDataAccessService::class => new Factory(WeatherDataAccessService::class, [
			CurrentWeatherDataHydrator::class => DependencyTypes::HYDRATOR,
			TemporalWeatherData::class => DependencyTypes::HYDRATOR,
			DailyWeatherData::class => DependencyTypes::HYDRATOR
		]),
		WeatherDataService::class => new Factory(WeatherDataService::class, [
			WeatherDataAccessService::class => DependencyTypes::SERVICE,
			DocumentManager::class => DependencyTypes::SERVICE
		])
	],
	'invokables' => [
		PasswordService::class => PasswordService::class,
		GUIDGenerator::class => GUIDGenerator::class
	]
];