<?php

use Perna\InputFilter\CityDumpInputFilter;
use Perna\InputFilter\LoginCredentialsInputFilter;
use Perna\InputFilter\RefreshInputFilter;
use Perna\InputFilter\UserInputFilter;
use Perna\InputFilter\UserPutInputFilter;

return [
	'invokables' => [
		CityDumpInputFilter::class => CityDumpInputFilter::class,
		LoginCredentialsInputFilter::class => LoginCredentialsInputFilter::class,
		UserInputFilter::class => UserInputFilter::class,
		UserPutInputFilter::class => UserPutInputFilter::class,
		RefreshInputFilter::class => RefreshInputFilter::class
	],
];