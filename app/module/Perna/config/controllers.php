<?php

use Perna\Controller\Calendar\CalendarsController;
use Perna\Controller\Calendar\EventsController;
use Perna\Controller\Console\ImportCitiesController;
use Perna\Controller\GoogleAuth\AuthUrlController;
use Perna\Controller\GoogleAuth\CallbackController;
use Perna\Controller\LoginController;
use Perna\Controller\LogoutController;
use Perna\Controller\ModuleController;
use Perna\Controller\ModulesController;
use Perna\Controller\PublicTransport\DepartureController;
use Perna\Controller\PublicTransport\StationSearchController;
use Perna\Controller\RefreshController;
use Perna\Controller\RegisterController;
use Perna\Controller\UserController;
use Perna\Controller\Weather\WeatherDataController;
use Perna\Controller\Weather\WeatherLocationAutocompleteController;
use Perna\Controller\Weather\WeatherLocationController;
use Perna\Controller\Weather\WeatherLocationNearbyController;
use Perna\Controller\Weather\WeatherLocationSearchController;
use Perna\Factory\DependencyTypes;
use Perna\Factory\Factory;
use Perna\Hydrator\UserHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\CityImportService;
use Perna\Service\GoogleAuthenticationService;
use Perna\Service\GoogleCalendarService;
use Perna\Service\ModuleService;
use Perna\Service\PublicTransport\DepartureService;
use Perna\Service\PublicTransport\StationsService;
use Perna\Service\PublicTransport\VBBAccessService;
use Perna\Service\UserService;
use Perna\Service\Weather\GeoNamesAccessService;
use Perna\Service\Weather\WeatherDataService;
use Perna\Service\WeatherLocationService;

return [
	'factories' => [
		RegisterController::class => new Factory(RegisterController::class, [
			AuthenticationService::class => DependencyTypes::SERVICE,
			UserService::class => DependencyTypes::SERVICE,
			UserHydrator::class => DependencyTypes::HYDRATOR
		]),
		UserController::class => new Factory(UserController::class, [
			AuthenticationService::class => DependencyTypes::SERVICE,
			UserService::class => DependencyTypes::SERVICE,
			UserHydrator::class => DependencyTypes::HYDRATOR
		]),
		LoginController::class => new Factory(LoginController::class, [
			AuthenticationService::class => DependencyTypes::SERVICE
		]),
		LogoutController::class => new Factory(LogoutController::class, [
			AuthenticationService::class => DependencyTypes::SERVICE
		]),
		RefreshController::class => new Factory(RefreshController::class, [
			AuthenticationService::class => DependencyTypes::SERVICE
		]),
		AuthUrlController::class => new Factory(AuthUrlController::class, [
			AuthenticationService::class => DependencyTypes::SERVICE,
			GoogleAuthenticationService::class => DependencyTypes::SERVICE
		]),
		CallbackController::class => new Factory(CallbackController::class, [
			GoogleAuthenticationService::class => DependencyTypes::SERVICE
		]),
		CalendarsController::class => new Factory(CalendarsController::class, [
			AuthenticationService::class => DependencyTypes::SERVICE,
			GoogleCalendarService::class => DependencyTypes::SERVICE
		]),
		EventsController::class => new Factory(EventsController::class, [
			AuthenticationService::class => DependencyTypes::SERVICE,
			GoogleCalendarService::class => DependencyTypes::SERVICE
		]),
		ModulesController::class => new Factory(ModulesController::class, [
			AuthenticationService::class => DependencyTypes::SERVICE,
			ModuleService::class => DependencyTypes::SERVICE
		]),
		ModuleController::class => new Factory(ModuleController::class, [
			AuthenticationService::class => DependencyTypes::SERVICE,
			ModuleService::class => DependencyTypes::SERVICE
    	]),
		WeatherLocationNearbyController::class => new Factory(WeatherLocationNearbyController::class, [
			AuthenticationService::class => DependencyTypes::SERVICE,
			GeoNamesAccessService::class => DependencyTypes::SERVICE
		]),
		WeatherLocationAutocompleteController::class => new Factory(WeatherLocationAutocompleteController::class, [
			AuthenticationService::class => DependencyTypes::SERVICE,
			WeatherLocationService::class => DependencyTypes::SERVICE
		]),
		WeatherDataController::class => new Factory(WeatherDataController::class, [
			AuthenticationService::class => DependencyTypes::SERVICE,
			WeatherDataService::class => DependencyTypes::SERVICE
		]),
		WeatherLocationController::class => new Factory(WeatherLocationController::class, [
			AuthenticationService::class => DependencyTypes::SERVICE,
			GeoNamesAccessService::class => DependencyTypes::SERVICE
		]),
		WeatherLocationSearchController::class => new Factory(WeatherLocationSearchController::class, [
			AuthenticationService::class => DependencyTypes::SERVICE,
			GeoNamesAccessService::class => DependencyTypes::SERVICE
		]),
		StationSearchController::class => new Factory(StationSearchController::class, [
			AuthenticationService::class => DependencyTypes::SERVICE,
			VBBAccessService::class => DependencyTypes::SERVICE,
		    StationsService::class => DependencyTypes::SERVICE
		]),
		DepartureController::class => new Factory(DepartureController::class, [
			AuthenticationService::class => DependencyTypes::SERVICE,
			DepartureService::class => DependencyTypes::SERVICE
		])
	]
];