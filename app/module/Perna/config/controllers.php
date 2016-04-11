<?php

use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Controller\Console\ImportCitiesController;
use Zend\Mvc\Controller\ControllerManager;

return [
	'factories' => [
		ImportCitiesController::class => function ( ControllerManager $controllerManager ) : ImportCitiesController {
			/** @var DocumentManager $dm */
			$dm = $controllerManager->getServiceLocator()->get( DocumentManager::class );
			return new ImportCitiesController( $dm );
		}
	]
];