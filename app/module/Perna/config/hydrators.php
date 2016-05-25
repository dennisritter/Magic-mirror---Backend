<?php

use Perna\Document\GoogleAccessToken;
use Perna\Document\GoogleCalendar;
use Perna\Hydrator\AccessTokenHydrator;
use Perna\Hydrator\CityDumpHydrator;
use Perna\Hydrator\CityHydrator;
use Perna\Hydrator\GoogleCalendarHydrator;
use Perna\Hydrator\GoogleEventHydrator;
use Perna\Hydrator\UserHydrator;
use Perna\Hydrator\UserTokenHydrator;

return [
	'invokables' => [
		CityDumpHydrator::class => CityDumpHydrator::class,
		UserHydrator::class => UserHydrator::class,
		UserTokenHydrator::class => UserTokenHydrator::class,
		AccessTokenHydrator::class => AccessTokenHydrator::class,
		GoogleAccessToken::class => GoogleAccessToken::class,
		GoogleCalendarHydrator::class => GoogleCalendarHydrator::class,
		GoogleEventHydrator::class => GoogleEventHydrator::class,
		CityHydrator::class => CityHydrator::class
	]
];