<?php

use Perna\InputFilter\CityDumpInputFilter;
use Perna\InputFilter\EventQuickAddInputFilter;
use Perna\InputFilter\LoginCredentialsInputFilter;
use Perna\InputFilter\ModuleInputFilter;
use Perna\InputFilter\RefreshInputFilter;
use Perna\InputFilter\UserInputFilter;
use Perna\InputFilter\UserPutInputFilter;

return [
	'invokables' => [
		CityDumpInputFilter::class => CityDumpInputFilter::class,
		LoginCredentialsInputFilter::class => LoginCredentialsInputFilter::class,
		UserInputFilter::class => UserInputFilter::class,
		UserPutInputFilter::class => UserPutInputFilter::class,
		RefreshInputFilter::class => RefreshInputFilter::class,
		EventQuickAddInputFilter::class => EventQuickAddInputFilter::class,
		ModuleInputFilter::class => ModuleInputFilter::class
	],
];