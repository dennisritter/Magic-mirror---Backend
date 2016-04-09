<?php

use Doctrine\ODM\MongoDB\DocumentManager;
use Zend\Di\ServiceLocator;
use Zend\ServiceManager\ServiceManager;

return [
	'factories' => [
		DocumentManager::class => function ( ServiceManager $serviceManager ) : DocumentManager {
			return $serviceManager->get('doctrine.documentmanager.perna');
		}
	]
];