<?php

use Perna\Controller\Console\ImportCitiesController;

return [
	'router' => [
		'routes' => [
			'import-cities' => [
				'options' => [
					'route' => 'import-cities <dumpPath> [--verbose|-v]',
					'defaults' => [
						'controller' => ImportCitiesController::class,
						'action' => ImportCitiesController::ACTION_IMPORT_CITIES
					]
				]
			]
		]
	]
];