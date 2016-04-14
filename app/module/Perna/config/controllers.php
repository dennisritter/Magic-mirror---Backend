<?php

use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Controller\Console\ImportCitiesController;
use Perna\Service\CityImportService;
use Zend\Mvc\Controller\ControllerManager;

return [
	'factories' => [
		ImportCitiesController::class => function ( ControllerManager $controllerManager ) : ImportCitiesController {
			/** @var CityImportService $importer */
			$importer = $controllerManager->getServiceLocator()->get( CityImportService::class );
			return new ImportCitiesController( $importer );
		}
	]
];