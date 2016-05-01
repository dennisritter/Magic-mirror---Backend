<?php

namespace Perna\InputFilter;

use Perna\Service\GUIDGenerator;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;

class RefreshInputFilter extends InputFilter {
	
	public function __construct () {
		$input = new Input();
		$input->getValidatorChain()->attach( new StringLength([
			'min' => GUIDGenerator::GUID_LENGTH,
			'max' => GUIDGenerator::GUID_LENGTH
		]) );

		$this->add( $input, 'accessToken' );
		$this->add( clone $input, 'refreshToken' );
	}
}