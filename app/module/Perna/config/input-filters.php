<?php

use Perna\InputFilter\CityDumpInputFilter;
use Perna\InputFilter\UserInputFilter;

return [
	'invokables' => [
		CityDumpInputFilter::class => CityDumpInputFilter::class
	],
	'factories' => [
		UserInputFilter::class => function () {
			return new UserInputFilter( ['passwordRequired' => false] );
		},
		UserInputFilter::REQUIRED_PASSWORD => function () {
			return new UserInputFilter( ['passwordRequired' => true] );
		}
	]
];