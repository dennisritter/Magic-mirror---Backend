<?php

use Perna\Controller\Plugin\ExtractObject;
use Zend\Di\ServiceLocatorInterface;
use Zend\Hydrator\HydratorPluginManager;
use Zend\Mvc\Controller\PluginManager;

return [
	'factories' => [
		ExtractObject::class => function ( PluginManager $pluginManager ) {
			/** @var ServiceLocatorInterface $parentLocator */
			$parentLocator = $pluginManager->getServiceLocator();
			/** @var HydratorPluginManager $hpm */
			$hpm = $parentLocator->get('HydratorManager');
			return new ExtractObject( $hpm );
		}
	],
	'aliases' => [
		'extractObject' => ExtractObject::class
	]
];