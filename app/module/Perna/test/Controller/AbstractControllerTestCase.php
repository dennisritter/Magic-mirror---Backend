<?php

namespace Perna\Test\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AbstractControllerTestCase extends AbstractHttpControllerTestCase {

	public function setUp () {
		$this->setApplicationConfig( include __DIR__ . '/../../../../config/application.config.php' );
		parent::setUp();
	}

	protected function getJSONResponse () {
		$response = $this->getResponse();
		$data = json_decode( $response->getContent(), true );

		if ( $data === false )
			throw new \AssertionError("The Response data is not valid JSON.");

		return $data;
	}

	protected function getSuccessResponseData () {
		$data = $this->getJSONResponse();
		$this->assertArrayHasKey('success', $data);
		$this->assertTrue( $data['success'] );
		$this->assertArrayHasKey('data', $data);
		return $data['data'];
	}

	protected function getErrorResponseContent ( int $status ) {
		$data = $this->getJSONResponse();
		$this->assertArrayHasKey('status_code', $data);
		$this->assertEquals( $status, $data['status_code'] );
		$this->assertArrayHasKey('message', $data);
		return $data;
	}

	protected function assertControllerIs ( $controllerClass ) {
		$this->assertControllerName( $controllerClass );

		if ( !class_exists( $controllerClass ) )
			throw new \AssertionError("The controller class {$controllerClass} does not exist.");

		$reflect = new \ReflectionClass( $controllerClass );
		$this->assertControllerClass( $reflect->getShortName() );
	}
}