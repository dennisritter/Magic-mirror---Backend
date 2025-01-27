<?php

namespace Perna\Test\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Perna\Document\AccessToken;
use Perna\Document\User;
use Perna\Service\GUIDGenerator;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
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

	const DUMMY_ACCESS_TOKEN = 'C57A25FF-A9E9-5870-2E2A-6A3156EFDB22';
	
	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $documentManager;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $documentRepository;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $httpClient;

	/** @inheritdoc */
	public function setUp () {
		parent::setUp();

		$this->setApplicationConfig( include __DIR__ . '/../../../../config/application.config.php' );
		$this->traceError = false;

		$this->documentRepository = $this->getMockBuilder( DocumentRepository::class )->disableOriginalConstructor()->getMock();

		$this->documentManager = $this->getMockBuilder( DocumentManager::class )->disableOriginalConstructor()->getMock();

		$this->documentManager
			->expects($this->any())
			->method('getRepository')
			->willReturn( $this->documentRepository );

		$this->httpClient = $this->getMockBuilder( Client::class )->disableOriginalConstructor()->getMock();

		$sm = $this->getApplicationServiceLocator();
		$sm->setAllowOverride( true );
		$sm->setService( DocumentManager::class, $this->documentManager );
		$sm->setService( Client::class, $this->httpClient );
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
		$statusCode = $this->getResponseStatusCode();
		if ( !in_array($statusCode, [200,201]) )
			$this->fail("Success responses must have the status code 200 or 201. Got {$statusCode}.");

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

	/**
	 * Generates a random GUID
	 * @return    string    The GUID
	 */
	protected function generateGUID () : string {
		$generator = new GUIDGenerator();
		return $generator->generateGUID();
	}

	/**
	 * Tests whether an error occurs when Access-Token ist not set
	 * @param     string    $endpoint The endpoint
	 * @param     string    $method   The HTTP method to use
	 */
	protected function abstractTestAccessTokenRequired ( string $endpoint, string $method ) {
		$this->dispatch( $endpoint, $method );
		$this->getErrorResponseContent(401);
	}

	/**
	 * Transforms associative array data
	 * @inheritdoc
	 * @param     array     $query    Associative array containing query parameters
	 */
	public function dispatch( $url, $method = null, $params = [], $query = [], $isXmlHttpRequest = false ) {
		/** @var Request $r */
		$r = $this->getRequest();

		if ( is_array( $query ) && count( $query ) > 0 ) {
			$r->setQuery( new Parameters( $query ) );
		}

		if ( is_array( $params ) && count( $params ) > 0
			&& in_array( $method, [Request::METHOD_POST, Request::METHOD_PATCH, Request::METHOD_PUT] ) ) {
			$r->setContent( json_encode( $params ) );
			$params = null;
		}

		parent::dispatch( $url, $method, [], $isXmlHttpRequest );
	}

	protected function getValidAccessToken ( bool $setMockExpectation = true ) : AccessToken {
		$user = new User();
		$user->setFirstName('Max');
		$user->setLastName('Mustermann');
		$user->setEmail('max@mustermann.de');
		$user->setLastLogin(new \DateTime('now'));

		$at = new AccessToken();
		$at->setToken( self::DUMMY_ACCESS_TOKEN );
		$at->setExpirationDate( (new \DateTime('now'))->add(new \DateInterval('PT1H')) );
		$at->setUser( $user );
		
		if ( $setMockExpectation ) {
			$this->documentRepository
				->expects($this->once())
				->method('find')
				->with( $this->equalTo(self::DUMMY_ACCESS_TOKEN) )
				->willReturn( $at );
		}

		return $at;
	}
}