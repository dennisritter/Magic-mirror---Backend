<?php

use Perna\Hydrator\CityDumpHydrator;
use Perna\Hydrator\UserHydrator;
use Perna\Hydrator\UserTokenHydrator;

return [
	'invokables' => [
		CityDumpHydrator::class => CityDumpHydrator::class,
		UserHydrator::class => UserHydrator::class,
		UserTokenHydrator::class => UserTokenHydrator::class
	]
];