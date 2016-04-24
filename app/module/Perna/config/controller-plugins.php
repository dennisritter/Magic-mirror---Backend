<?php

use Perna\Controller\Plugin\ExtractObject;
use Zend\Di\ServiceLocatorInterface;
use Zend\Hydrator\HydratorPluginManager;

return [
	'factories' => [
		ExtractObject::class => function ( ServiceLocatorInterface $serviceLocator ) {
			/** @var ServiceLocatorInterface $parentLocator */
			$parentLocator = $serviceLocator->getServiceLocator();
			/** @var HydratorPluginManager $hpm */
			$hpm = $parentLocator->get('HydratorManager');
			return new ExtractObject( $hpm );
		}
	],
	'aliases' => [
		'extractObject' => ExtractObject::class
	]
];