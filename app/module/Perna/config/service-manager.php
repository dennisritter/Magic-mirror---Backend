<?php

use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Hydrator\CityDumpHydrator;
use Perna\InputFilter\CityDumpInputFilter;
use Perna\Service\CityImportService;
use Zend\Di\ServiceLocator;
use Zend\ServiceManager\ServiceManager;

return [
	'aliases' => [
		DocumentManager::class => 'doctrine.documentmanager.odm_default'
	],
	'factories' => [
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
		}
	]
];