<?php

namespace Perna\InputFilter;

use Zend\Filter\StringTrim;
use Zend\InputFilter\Input;
use Zend\Validator\EmailAddress;
use Zend\Validator\StringLength;

class InputFactory {

	public static function createNameInput ( string $name ) : Input {
		$input = new Input( $name );
		$input->getValidatorChain()->attach( new StringLength([
			'min' => 2,
			'max' => 100
		]) );
		$input->getFilterChain()->attach( new StringTrim() );

		return $input;
	}

	public static function createEmailInput ( string $name = 'email' ) : Input {
		$input = new Input( $name );
		$input->getFilterChain()->attach( new StringTrim() );
		$input->getValidatorChain()->attach( new EmailAddress() );

		return $input;
	}
}