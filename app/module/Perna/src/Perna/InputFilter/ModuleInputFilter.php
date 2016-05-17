<?php

namespace Perna\InputFilter;

use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;

class ModuleInputFilter extends InputFilter {
		
	public function __construct() {
		$this->add( InputFactory::createNameInput('width') );
		$this->add( InputFactory::createNameInput('height') );
		$this->add( InputFactory::createNameInput('xPosition') );
		$this->add( InputFactory::createNameInput('yPosition') );
		$this->add( InputFactory::createNameInput('type') );
	}
}