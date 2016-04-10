<?php

return [
	'doctrine' => [
		'connection' => [
			'perna' => [
				'server'           => 'localhost',
				'port'             => '27017',
				'connectionString' => null,
				'user'             => 'perna',
				'password'         => 'spiegelvogelSS2015',
				'dbname'           => 'perna',
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

				'default_db'         => 'perna',

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