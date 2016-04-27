<?php

namespace Perna\InputFilter;

use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;

/**
 * Input Filter for Login Credentials
 *
 * @author      Jannik Portz
 * @package     Perna\InputFilter
 */
class LoginCredentialsInputFilter extends InputFilter {

	public function __construct () {
		$this->add( InputFactory::createEmailInput() );

		$password = new Input('password');
		$password->getValidatorChain()->attach( new StringLength([
			'min' => 6
		]) );
		$this->add( $password );
	}
}