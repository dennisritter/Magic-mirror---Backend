<?php

use Zend\Mvc\Router\Http\Literal;

return [
	'routes' => [
		'v1' => [
			'type' => Literal::class,
			'options' => [
				'route' => '/v1'
			],
			'may_terminate' => false,
			'child_routes' => $this->getConfigFor('api-routes')
		]
	]
];