<?php

use Perna\Controller\Plugin\ExtractObject;
use Perna\Factory\DependencyTypes;
use Perna\Factory\Factory;
use Zend\Di\ServiceLocatorInterface;
use Zend\Hydrator\HydratorPluginManager;
use Zend\Mvc\Controller\PluginManager;

return [
	'factories' => [
		ExtractObject::class => new Factory(ExtractObject::class, [
			'HydratorManager' => DependencyTypes::SERVICE
		])
	],
	'aliases' => [
		'extractObject' => ExtractObject::class
	]
];