<?php

use Perna\Controller\Calendar\CalendarsController;
use Perna\Controller\Calendar\EventsController;
use Perna\Controller\GoogleAuth\AuthUrlController;
use Perna\Controller\GoogleAuth\CallbackController;
use Perna\Controller\LoginController;
use Perna\Controller\LogoutController;
use Perna\Controller\ModuleController;
use Perna\Controller\ModulesController;
use Perna\Controller\PublicTransport\DepartureController;
use Perna\Controller\PublicTransport\StationsController;
use Perna\Controller\PublicTransport\StationSearchController;
use Perna\Controller\RefreshController;
use Perna\Controller\RegisterController;
use Perna\Controller\UserController;
use Perna\Controller\Weather\WeatherDataController;
use Perna\Controller\Weather\WeatherLocationController;
use Perna\Controller\Weather\WeatherLocationNearbyController;
use Perna\Controller\Weather\WeatherLocationSearchController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
	'register' => [
		'type' => Literal::class,
		'options' => [
			'route' => '/register',
			'defaults' => [
				'controller' => RegisterController::class
			]
		]
	],
	'user' => [
		'type' => Literal::class,
		'options' => [
			'route' => '/user',
			'defaults' => [
				'controller' => UserController::class
			]
		]
	],

	'login' => [
		'type' => Literal::class,
		'options' => [
			'route' => '/login',
			'defaults' => [
				'controller' => LoginController::class
			]
		]
	],

	'logout' => [
		'type' => Literal::class,
		'options' => [
			'route' => '/logout',
			'defaults' => [
				'controller' => LogoutController::class
			]
		]
	],

	'refresh' => [
		'type' => Literal::class,
		'options' => [
			'route' => '/refresh',
			'defaults' => [
				'controller' => RefreshController::class
			]
		]
	],

	'googleAuth' => [
		'type' => Literal::class,
		'options' => [
			'route' => '/google-auth'
		],
		'may_terminate' => false,
		'child_routes' => [
			'authUrl' => [
				'type' => Literal::class,
				'options' => [
					'route' => '/auth-url',
					'defaults' => [
						'controller' => AuthUrlController::class
					]
				]
			],

			'authCallback' => [
				'type' => Literal::class,
				'options' => [
					'route' => '/callback',
					'defaults' => [
						'controller' => CallbackController::class
					]
				]
			]
		]
	],

	'calendar' => [
		'type' => Literal::class,
		'options' => [
			'route' => '/calendar'
		],
		'may_terminate' => false,
		'child_routes' => [
			'calendars' => [
				'type' => Literal::class,
				'options' => [
					'route' => '/calendars',
					'defaults' => [
						'controller' => CalendarsController::class
					]
				]
			],

			'events' => [
				'type' => Literal::class,
				'options' => [
					'route' => '/events',
					'defaults' => [
						'controller' => EventsController::class
					]
				]
			]
		]
	],

	'modules' => [
		'type' => Literal::class,
		'options' => [
			'route' => '/modules',
			'defaults' => [
				'controller' => ModulesController::class
			]
		],
		'may_terminate' => true,
		'child_routes' => [
			'id' => [
				'type' => Segment::class,
				'options' => [
					'route' => '/:id',
					'defaults' => [
						'controller' => ModuleController::class
					],
					'constraints' => [
						'id' => '.+'
					]
				]
			]
		]
	],

	'weather' => [
		'type' => Literal::class,
		'options' => [
			'route' => '/weather'
		],
		'may_terminate' => false,
		'child_routes' => [
			'locations' => [
				'type' => Literal::class,
				'options' => [
					'route' => '/locations'
				],
				'may_terminate' => false,
				'child_routes' => [
					'nearby' => [
						'type' => Literal::class,
						'options' => [
							'route' => '/nearby',
							'defaults' => [
								'controller' => WeatherLocationNearbyController::class
							]
						]
					],
					'resource' => [
						'type' => Segment::class,
						'options' => [
							'route' => '/:id',
							'defaults' => [
								'controller' => WeatherLocationController::class
							],
							'constraints' => [
								'id' => '[0-9]+'
							]
						]
					],

					'search' => [
						'type' => Literal::class,
						'options' => [
							'route' => '/search',
							'defaults' => [
								'controller' => WeatherLocationSearchController::class
							]
						]
					]
				]
			],

			'weatherData' => [
				'type' => Segment::class,
				'options' => [
					'route' => '/:id',
					'defaults' => [
						'controller' => WeatherDataController::class
					],
					'constraints' => [
						'id' => '[0-9]+'
					]
				]
			]
		]
	],

	'publicTransport' => [
		'type' => Literal::class,
		'options' => [
			'route' => '/publicTransport',
		],
		'may_terminate' => false,
		'child_routes' => [
			'stations' => [
				'type' => Literal::class,
				'options' => [
					'route' => '/stations'
				],
				'may_terminate' => false,
				'child_routes' => [
					'searchStations' => [
						'type' => Literal::class,
						'options' => [
							'route' => '/search',
							'defaults' => [
								'controller' => StationSearchController::class
							]
						]
					],
					'singleStation' => [
						'type' => Segment::class,
						'options' => [
							'route' => '/:id',
							'defaults' => [
								'controller' => StationsController::class
							],
							'constraints' => [
								'id' => '[0-9]+'
							]
						]
					]
				]
			],
			'departures' => [
				'type' => Segment::class,
				'options' => [
					'route' => '/departures/:id',
					'defaults' => [
						'controller' => DepartureController::class
					],
					'constraints' => [
						'id' => '[0-9]+'
					]
				]
			]
		]
	]
];