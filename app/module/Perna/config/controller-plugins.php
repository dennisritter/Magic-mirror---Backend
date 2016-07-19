<?php

use Perna\Controller\Plugin\ExtractObject;
use Perna\Factory\DependencyTypes;
use Perna\Factory\Factory;

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