<?php

use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Factory\DependencyTypes;
use Perna\Factory\Factory;
use Perna\Hydrator\CityDumpHydrator;
use Perna\InputFilter\CityDumpInputFilter;
use Perna\Service\AuthenticationService;
use Perna\Service\CityImportService;
use Perna\Service\GUIDGenerator;
use Perna\Service\PasswordService;
use Perna\Service\UserService;
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
		])
	],
	'invokables' => [
		PasswordService::class => PasswordService::class,
		GUIDGenerator::class => GUIDGenerator::class
	]
];