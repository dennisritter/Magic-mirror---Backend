<?php

namespace Perna\InputFilter;

use Zend\Filter\StringTrim;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;

class EventQuickAddInputFilter extends InputFilter {

	public function __construct () {
		$stringTrim = new StringTrim();
		$stringLength = new StringLength([
			'min' => 1
		]);

		$text = new Input('text');
		$text->getFilterChain()->attach( $stringTrim );
		$text->getValidatorChain()->attach( $stringLength );
		$this->add( $text );

		$calendar = new Input('calendar');
		$calendar->setRequired( false );
		$calendar->getFilterChain()->attach( $stringTrim );
		$calendar->getValidatorChain()->attach( $stringLength );
		$this->add( $calendar );
	}
}