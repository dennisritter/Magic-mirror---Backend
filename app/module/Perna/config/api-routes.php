<?php

use Perna\Controller\UserController;
use Zend\Mvc\Router\Http\Literal;

return [
	'register' => [
		'type' => Literal::class,
		'options' => [
			'route' => '/register',
			'defaults' => [
				'controller' => UserController::class
			]
		]
	]
];