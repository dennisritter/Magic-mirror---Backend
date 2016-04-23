<?php

namespace Perna\InputFilter;


use Zend\Filter\StringTrim;
use Zend\InputFilter\Input;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;

class InputFactory {

	const NAME_PATTERN = '/^([ \u00c0-\u01ffa-zA-Z\'\-])+$/';

	public static function createNameInput( string $name ) {
		$input = new Input( $name );
		$input->getValidatorChain()->attach( new StringLength([
			'min' => 2,
			'max' => 100
		]) )->attach( new Regex( self::NAME_PATTERN ) );
		$input->getFilterChain()->attach( new StringTrim() );

		return $input;
	}
}