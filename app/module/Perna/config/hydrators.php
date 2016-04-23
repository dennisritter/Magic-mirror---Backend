<?php

use Perna\Hydrator\CityDumpHydrator;
use Perna\Hydrator\UserHydrator;

return [
	'invokables' => [
		CityDumpHydrator::class => CityDumpHydrator::class,
		UserHydrator::class => UserHydrator::class
	]
];