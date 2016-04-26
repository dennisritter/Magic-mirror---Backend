<?php

namespace Perna\InputFilter;

use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;

class UserInputFilter extends InputFilter {
	
	const REQUIRED_PASSWORD = self::class.'PasswordRequired';
	
	public function __construct( array $args ) {
		$this->add( InputFactory::createNameInput('firstName') );

		$this->add( InputFactory::createNameInput('lastName') );

		$this->add( InputFactory::createEmailInput() );

		//todo Better password validation
		$password = new Input('password');
		$password->getValidatorChain()->attach( new StringLength([
				'min' => 6
			]) );
		$password->setRequired( $args[ 'passwordRequired' ] ?? false );
		$this->add( $password );
	}
}