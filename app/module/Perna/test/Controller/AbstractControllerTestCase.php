<?php

namespace Perna\Test\Controller;

use Zend\Http\Request;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AbstractControllerTestCase extends AbstractHttpControllerTestCase {

	const HTTP_METHODS = [
		Request::METHOD_GET,
		Request::METHOD_POST,
		Request::METHOD_PUT,
		Request::METHOD_DELETE,
		Request::METHOD_PATCH,
		Request::METHOD_HEAD,
		Request::METHOD_TRACE,
		Request::METHOD_CONNECT,
		Request::METHOD_PROPFIND
	];

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
			$this->fail("The controller class {$controllerClass} does not exist.");

		$reflect = new \ReflectionClass( $controllerClass );
		$this->assertControllerClass( $reflect->getShortName() );
	}

	/**
	 * Generates a random String
	 * @param     int       $length   The length of the random string
	 * @return    string              The random string
	 */
	protected function randomString ( int $length ) : string {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$str = '';
		for ( $i = 0; $i < $length; ++$i ) {
			$str .= $characters[(int) rand(0, strlen($characters) - 1)];
		}
		return $str;
	}

	/**
	 * Asserts that the specified value is a GUID
	 * @param     string    $guid     The value to check
	 */
	protected function assertGUID ( $guid ) {
		if ( !is_string( $guid ) )
			$this->fail(sprintf("GUID must be a string, got %s", gettype($guid)));

		$regex = '/^[0-9A-F]{8}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{12}$/';
		if ( preg_match( $regex, $guid ) === false )
			$this->fail("string {$guid} is not a valid GUID.");
	}

	/**
	 * Sets a line in the request header
	 * @param     string    $name     The header name
	 * @param     string    $content  The header content
	 */
	protected function setRequestHeaderLine ( string $name, $content ) {
		/** @var Request $request */
		$request = $this->getRequest();
		$request->getHeaders()->addHeaderLine( $name, $content );
	}

	/**
	 * Asserts that all methods not contained in $allowedMethods are not allowed
	 *
	 * @param     string    $endpoint         The endpoint to call
	 * @param     string[]  $allowedMethods  Array of method names that are allowed
	 */
	protected function assertOtherMethodsNotAllowed ( string $endpoint, array $allowedMethods ) {
		foreach ( self::HTTP_METHODS as $method ) {
			if ( in_array( $method, $allowedMethods ) )
				continue;

			$this->dispatch( $endpoint, $method );
			$this->assertResponseStatusCode( 405 );
		}
	}
}