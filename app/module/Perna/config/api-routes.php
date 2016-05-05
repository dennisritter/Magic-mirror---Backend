<?php

use Perna\Controller\GoogleAuth\AuthUrlController;
use Perna\Controller\LoginController;
use Perna\Controller\LogoutController;
use Perna\Controller\RefreshController;
use Perna\Controller\UserController;
use Perna\Controller\RegisterController;
use Zend\Mvc\Router\Http\Literal;

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
			]
		]
	]
];