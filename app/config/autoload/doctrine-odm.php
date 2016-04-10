<?php

use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

return [
	'doctrine' => [
		'connection' => [
			'perna' => [
				'server'           => 'localhost',
				'port'             => '27017',
				'connectionString' => null,
				'user'             => null,
				'password'         => null,
				'dbname'           => null,
				'options'          => [ ]
			],
		],

		'configuration' => [
			'perna' => [
				'metadata_cache'     => 'array',

				'driver'             => 'perna',

				'generate_proxies'   => true,
				'proxy_dir'          => 'data/DoctrineMongoODMModule/Proxy',
				'proxy_namespace'    => 'DoctrineMongoODMModule\Proxy',

				'generate_hydrators' => true,
				'hydrator_dir'       => 'data/DoctrineMongoODMModule/Hydrator',
				'hydrator_namespace' => 'DoctrineMongoODMModule\Hydrator',

				'default_db'         => null,

				'logger'             => null
			]
		],

		'documentmanager' => [
			'perna' => [
				'connection'    => 'perna',
				'configuration' => 'perna',
				'eventmanager' => 'perna'
			]
		],

		'eventmanager' => [
			'perna' => [
				'subscribers' => [ ]
			]
		]
	]
];