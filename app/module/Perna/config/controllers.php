<?php

use Perna\Controller\Console\ImportCitiesController;
use Perna\Controller\RefreshController;
use Perna\Controller\RegisterController;
use Perna\Controller\LoginController;
use Perna\Controller\LogoutController;
use Perna\Controller\UserController;
use Perna\Factory\DependencyTypes;
use Perna\Factory\Factory;
use Perna\Hydrator\UserHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\CityImportService;
use Perna\Service\UserService;

return [
	'factories' => [
		ImportCitiesController::class => new Factory(ImportCitiesController::class, [
			CityImportService::class => DependencyTypes::SERVICE
		]),
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
		])
	]
];