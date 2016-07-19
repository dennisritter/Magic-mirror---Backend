<?php

namespace Perna\InputFilter;

use Zend\I18n\Validator\IsFloat;
use Zend\I18n\Validator\IsInt;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\GreaterThan;
use Zend\Validator\StringLength;

class CityDumpInputFilter extends InputFilter {

	public function __construct () {
		$id = new Input('_id');
		$id->getValidatorChain()
			->attach( new IsInt() )
			->attach( new GreaterThan(['min' => 1, 'inclusive' => true]) );
		$this->add( $id );

		$this->add( new Input('name') );

		$country = new Input('country');
		$country->getValidatorChain()
			->attach( new StringLength(['min' => 2, 'max' => 2]) );
		$this->add( $country );

		$coordsFilter = new InputFilter();
		$lat = new Input('lat');
		$lat->getValidatorChain()->attach( new IsFloat() );
		$coordsFilter->add( $lat );
		$lon = new Input('lon');
		$lon->getValidatorChain()->attach( new IsFloat() );
		$coordsFilter->add( $lon );

		$this->add( $coordsFilter, 'coord' );
 	}
}