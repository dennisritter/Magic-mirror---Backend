<?php

use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

return [
	'driver' => [
		'perna_documents' => [
			'class' => AnnotationDriver::class,
			'cache' => 'array',
			'paths' => [
				__DIR__ . '/../../src/Perna/Document'
			]
		],

		'perna' => [
			'drivers' => [
				'Perna\Document' => 'perna_documents'
			]
		]
	],
];