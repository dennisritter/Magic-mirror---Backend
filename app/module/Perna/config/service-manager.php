<?php

use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Hydrator\CityDumpHydrator;
use Perna\InputFilter\CityDumpInputFilter;
use Perna\Service\CityImportService;
use Perna\Service\PasswordService;
use Perna\Service\UserService;
use Zend\Di\ServiceLocator;
use Zend\ServiceManager\ServiceManager;

return [
	'factories' => [
		DocumentManager::class => function ( ServiceManager $serviceManager ) : DocumentManager {
			return $serviceManager->get('doctrine.documentmanager.odm_default');
		},
		CityImportService::class => function ( ServiceManager $serviceManager ) : CityImportService {
			/**
			 * @var DocumentManager $dm
			 * @var CityDumpHydrator $hydrator
			 * @var CityDumpInputFilter $inputFilter
			 */
			$dm = $serviceManager->get( DocumentManager::class );
			$hydrator = $serviceManager->get('HydratorManager')->get( CityDumpHydrator::class );
			$inputFilter = $serviceManager->get('InputFilterManager')->get( CityDumpInputFilter::class );
			return new CityImportService( $dm, $hydrator, $inputFilter );
		},
		UserService::class => function ( ServiceManager $serviceManager ) : UserService {
			/**
			 * @var PasswordService $ps
			 * @var DocumentManager $dm
			 */
			$ps = $serviceManager->get( PasswordService::class );
			$dm = $serviceManager->get( DocumentManager::class );
			return new UserService( $ps, $dm );
		}
	],
	'invokables' => [
		PasswordService::class => PasswordService::class
	]
];