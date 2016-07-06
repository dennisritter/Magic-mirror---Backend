<?php

namespace Perna\Test\Controller\Weather;


use Perna\Test\Controller\AbstractControllerTestCase;
use Zend\Http\Client;

class AbstractWeatherTest extends AbstractControllerTestCase {

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $clientMock;

	public function setUp() {
		$this->clientMock = $this->getMockBuilder( Client::class )->disableOriginalConstructor()->getMock();

		parent::setUp();
	}
}