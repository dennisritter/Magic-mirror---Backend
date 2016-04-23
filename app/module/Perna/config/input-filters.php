<?php

use Perna\InputFilter\CityDumpInputFilter;
use Perna\InputFilter\UserInputFilter;

return [
	'invokables' => [
		CityDumpInputFilter::class => CityDumpInputFilter::class,
		UserInputFilter::class => UserInputFilter::class
	]
];