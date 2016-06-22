<?php

namespace Perna\Test\Controller\User;

use Perna\Controller\LoginController;
use Perna\Document\AccessToken;
use Perna\Document\User;
use Zend\Http\Request;

class LoginControllerTest extends AbstractUserControllerTestCase {

	const ENDPOINT = '/v1/login';

	const DUMMY_DATA = [
		'email' => 'meine@emailadresse.de',
		'password' => 'meinpassword'
	];
	
	public function testSuccess () {
		$user = new User();
		$user->setEmail('meine@emailadresse.de');
		$user->setPassword( $this->generatePasswordHash( self::DUMMY_DATA['password'] ) );

		$this->documentRepository
			->expects( $this->once() )
			->method('findOneBy')
			->with( $this->equalTo(['email' => self::DUMMY_DATA['email']]) )
			->willReturn( $user );

		$this->documentManager
			->expects( $this->once() )
			->method('persist')
			->with( $this->isInstanceOf( AccessToken::class ) );

		$this->documentManager
			->expects( $this->once() )
			->method('flush');

		$this->dispatch( self::ENDPOINT, Request::METHOD_POST, self::DUMMY_DATA );
		$this->assertControllerIs( LoginController::class );
		$this->assertResponseStatusCode( 201 );
		$data = $this->getSuccessResponseData();

		$this->assertArrayHasKey('token', $data);
		$this->assertGUID( $data['token'] );
		$this->assertArrayHasKey('expirationDate', $data);
		$this->assertEquals( (new \DateTime('now'))->add(new \DateInterval('P1D')), new \DateTime($data['expirationDate']) );

		$this->assertArrayHasKey('refreshToken', $data);
		$rt = $data['refreshToken'];
		$this->assertArrayHasKey('token', $rt);
		$this->assertGUID($rt['token']);
		$this->assertArrayHasKey('expirationDate', $rt);
		$this->assertEquals( (new \DateTime('now'))->add(new \DateInterval('P2D')), new \DateTime($rt['expirationDate']) );
	}

	public function testUnknownUser () {
		$this->documentRepository
			->expects( $this->once() )
			->method('findOneBy')
			->with( $this->equalTo(['email' => self::DUMMY_DATA['email']]) )
			->willReturn( null );

		$this->documentManager
			->expects( $this->never() )
			->method('persist');

		$this->documentManager
			->expects( $this->never() )
			->method('flush');

		$this->dispatch( self::ENDPOINT, Request::METHOD_POST, self::DUMMY_DATA );
		$this->assertControllerIs( LoginController::class );
		$this->getErrorResponseContent(422);
	}

	public function testPasswordIncorrect () {
		$user = new User();
		$user->setPassword( $this->generatePasswordHash( 'meinpasswor' ) );

		$this->documentRepository
			->expects( $this->once() )
			->method('findOneBy')
			->with( $this->equalTo(['email' => self::DUMMY_DATA['email']]) )
			->willReturn( $user );

		$this->documentManager
			->expects( $this->never() )
			->method('persist');

		$this->documentManager
			->expects( $this->never() )
			->method('flush');

		$this->dispatch( self::ENDPOINT, Request::METHOD_POST, self::DUMMY_DATA );
		$this->assertControllerIs( LoginController::class );
		$this->getErrorResponseContent(422);
	}

	protected function abstractValidationErrorTest ( array $data ) {
		$this->documentRepository
			->expects( $this->never() )
			->method('findOneBy');

		$this->documentManager
			->expects( $this->never() )
			->method('persist');

		$this->documentManager
			->expects( $this->never() )
			->method('flush');

		$this->dispatch( self::ENDPOINT, Request::METHOD_POST, $data );
		$this->assertControllerIs( LoginController::class );
		$this->assertResponseStatusCode( 422 );
	}

	public function testEmailMissing () {
		$this->abstractValidationErrorTest([
			'password' => 'xyz'
		]);
	}

	public function testPasswordMissing () {
		$this->abstractValidationErrorTest([
			'email' => 'meine@emailadresse.de'
		]);
	}
	
	public function testMethodsNotAllowed () {
		$this->assertOtherMethodsNotAllowed( self::ENDPOINT, [Request::METHOD_POST] );
	}
}