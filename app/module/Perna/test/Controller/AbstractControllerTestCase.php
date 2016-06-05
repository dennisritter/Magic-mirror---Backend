<?php

namespace Perna\Test\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AbstractControllerTestCase extends AbstractHttpControllerTestCase {

	/** @inheritdoc */
	public function setUp () {
		$this->setApplicationConfig( include __DIR__ . '/../../../../config/application.config.php' );
		$this->traceError = false;
		parent::setUp();
	}

	/**
	 * Parses the JSON Response-Content and returns it
	 * @return    mixed     The JSON data in the response
	 * @throws    \AssertionError   If the response data is no valid JSON
	 */
	protected function getJSONResponse () {
		$response = $this->getResponse();
		$data = json_decode( $response->getContent(), true );

		if ( $data === false )
			throw new \AssertionError("The Response data is not valid JSON.");

		return $data;
	}

	/**
	 * Returns the parsed Response data and makes assertions concerning success responses
	 * @return    mixed     The response data under the 'data' key
	 */
	protected function getSuccessResponseData () {
		$data = $this->getJSONResponse();
		$this->assertArrayHasKey('success', $data);
		$this->assertTrue( $data['success'] );
		$this->assertArrayHasKey('data', $data);
		return $data['data'];
	}

	/**
	 * Returns the parsed Response data and males assertions concerning error responses
	 * @param     int       $status   The expected status code
	 * @return    mixed               The whole response content
	 */
	protected function getErrorResponseContent ( int $status ) {
		$data = $this->getJSONResponse();
		$this->assertArrayHasKey('status_code', $data);
		$this->assertEquals( $status, $data['status_code'] );
		$this->assertArrayHasKey('message', $data);
		return $data;
	}

	/**
	 * Makes controller assertions
	 * @param     string    $controllerClass  The full controller class
	 * @throws    \AssertionError             If the expected and actual controller do not match
	 */
	protected function assertControllerIs ( $controllerClass ) {
		$this->assertControllerName( $controllerClass );

		if ( !class_exists( $controllerClass ) )
			throw new \AssertionError("The controller class {$controllerClass} does not exist.");

		$reflect = new \ReflectionClass( $controllerClass );
		$this->assertControllerClass( $reflect->getShortName() );
	}
}