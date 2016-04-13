<?php

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Tests\Functional\City;
use Perna\Hydrator\CityDumpHydrator;
use Perna\InputFilter\CityDumpInputFilter;
use Perna\Service\CityImportService;
use Zend\Di\ServiceLocator;
use Zend\Json\Server\Smd\Service;
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
		}
	]
];