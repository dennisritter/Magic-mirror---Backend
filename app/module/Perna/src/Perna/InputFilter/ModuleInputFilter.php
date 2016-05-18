<?php

namespace Perna\InputFilter;

use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;

class ModuleInputFilter extends InputFilter {
		
	public function __construct() {
		$this->add( new Input('width') );
		$this->add( new Input('height') );
		$this->add( new Input('xPosition') );
		$this->add( new Input('yPosition') );
		$this->add( new Input('type') );
		$this->add( new Input('calendarIds') );
	}
}