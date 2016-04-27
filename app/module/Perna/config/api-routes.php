<?php

use Perna\Controller\LoginController;
use Perna\Controller\LogoutController;
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
	]
];