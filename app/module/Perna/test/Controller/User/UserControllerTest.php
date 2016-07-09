<?php

namespace Perna\Test\Controller\User;

use Perna\Controller\UserController;
use Perna\Document\AccessToken;
use Perna\Document\User;
use Zend\Http\Request;

class UserControllerTest extends AbstractUserControllerTestCase {

	const ENDPOINT = '/v1/user';
	const DUMMY_DATA = [
		'firstName' => 'Peter',
		'lastName' => 'Lustig',
		'email' => 'peter@lustig.de'
	];

	public function setUp () {
		parent::setUp();

		$this->documentManager
			->method('getRepository')
			->with( $this->equalTo( AccessToken::class ) )
			->willReturn( $this->documentRepository );
	}
	
	public function testGetSuccess () {
		$lastLogin = (new \DateTime('now'))->sub(new \DateInterval('P23D'));
		$at = $this->getValidAccessToken();
		$at->getUser()->setLastLogin( $lastLogin );

		$this->setRequestHeaderLine('Access-Token', self::DUMMY_ACCESS_TOKEN);
		$this->dispatch( self::ENDPOINT, Request::METHOD_GET );
		$this->assertControllerIs( UserController::class );
		$data = $this->getSuccessResponseData();
		$this->assertArrayHasKey('firstName', $data);
		$this->assertArrayHasKey('lastName', $data);
		$this->assertArrayHasKey('email', $data);
		$this->assertArrayHasKey('lastLogin', $data);
		$this->assertEquals('Max', $data['firstName']);
		$this->assertEquals('Mustermann', $data['lastName']);
		$this->assertEquals('max@mustermann.de', $data['email']);
		$this->assertEquals($lastLogin->format( \DateTime::RFC3339 ), $data['lastLogin']);
	}

	public function testPutSuccess () {
		$newData = [
			'firstName' => 'Peter',
			'lastName' => 'Lustig',
			'email' => 'peter@lustig.de'
		];


		$this->documentManager->expects($this->once())->method('flush');
		$userMock = $this->getMockBuilder( User::class )->disableOriginalConstructor()->getMock();

		foreach ( $newData as $key => $value ) {
			$pascal = strtoupper(substr($key, 0, 1)).substr($key,1);
			$userMock->expects($this->once())->method('set'.$pascal)->with($this->equalTo($value));
			$userMock->expects($this->any())->method('get'.$pascal)->willReturn($value);
		}

		/** @var User $userMock */
		$ac = $this->getValidAccessToken();
		$ac->setUser( $userMock );

		$this->documentRepository->expects($this->once())->method('find')->with($this->equalTo($ac->getToken()));

		$this->setRequestHeaderLine( 'Access-Token', self::DUMMY_ACCESS_TOKEN );
		$this->dispatch( self::ENDPOINT, Request::METHOD_PUT, $newData );
		$this->assertControllerIs( UserController::class );
		$data = $this->getSuccessResponseData();

		$this->assertArrayHasKey('firstName', $data);
		$this->assertArrayHasKey('lastName', $data);
		$this->assertArrayHasKey('email', $data);
		$this->assertArrayHasKey('lastLogin', $data);
		$this->assertEquals('Peter', $data['firstName']);
		$this->assertEquals('Lustig', $data['lastName']);
		$this->assertEquals('peter@lustig.de', $data['email']);
	}

	protected function abstractPutValidationTest ( $data, $property ) {
		$this->documentManager->expects($this->never())->method('flush');
		$this->documentManager->expects($this->never())->method('getRepository');
		$this->documentRepository->expects($this->never())->method('find');

		$this->setRequestHeaderLine( 'Access-Token', self::DUMMY_ACCESS_TOKEN );
		$this->dispatch( self::ENDPOINT, Request::METHOD_PUT, $data );
		$this->assertControllerIs(UserController::class);
		$data = $this->getErrorResponseContent(422);
		$this->assertArrayHasKey('errors', $data);
		$errors = $data['errors'];
		$this->assertEquals(1, count($errors));
		$this->assertArrayHasKey($property, $errors);
	}

	public function testPutFirstNameMissing () {
		$data = self::DUMMY_DATA;
		unset($data['firstName']);
		$this->abstractPutValidationTest($data, 'firstName');
	}

	public function testPutLastNameMissing () {
		$data = self::DUMMY_DATA;
		unset($data['lastName']);
		$this->abstractPutValidationTest($data, 'lastName');
	}

	public function testPutEmailMissing () {
		$data = self::DUMMY_DATA;
		unset($data['email']);
		$this->abstractPutValidationTest($data, 'email');
	}

	public function testMethodsNotAllowed () {
		$this->assertOtherMethodsNotAllowed( self::ENDPOINT, [Request::METHOD_GET, Request::METHOD_PUT] );
	}

	public function testGETAccessTokenRequired () {
		$this->abstractTestAccessTokenRequired( self::ENDPOINT, Request::METHOD_GET );
	}

	public function testPUTAccessTokenRequired () {
		$this->abstractTestAccessTokenRequired( self::ENDPOINT, Request::METHOD_PUT );
	}
}