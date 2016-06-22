<?php

namespace Perna\Test\Controller\User;


use Perna\Controller\RefreshController;
use Perna\Document\AccessToken;
use Perna\Document\RefreshToken;
use Perna\Document\User;
use Zend\Http\Request;

class RefreshControllerTest extends AbstractUserControllerTestCase {

	const DUMMY_REFRESH_TOKEN = '3603F216-733C-4E62-B607-A055C2F6CDD2';
	const ENDPOINT = '/v1/refresh';

	public function setUp() {
		parent::setUp();

		$this->documentManager
			->method('getRepository')
			->with( $this->equalTo( AccessToken::class ) )
			->willReturn( $this->documentRepository );
	}

	public function testSuccess () {
		$at = new AccessToken();
		$at->setToken( self::DUMMY_ACCESS_TOKEN );
		$at->setExpirationDate( (new \DateTime('now'))->sub( new \DateInterval('PT12H') ) );
		$rt = new RefreshToken();
		$rt->setToken( self::DUMMY_REFRESH_TOKEN );
		$rt->setExpirationDate( (new \DateTime('now'))->add( new \DateInterval('PT12H') ) );
		$at->setRefreshToken( $rt );
		$at->setUser( new User() );

		$this->documentRepository
			->expects( $this->once() )
			->method('find')
			->with( $this->equalTo( self::DUMMY_ACCESS_TOKEN ) )
			->willReturn( $at );

		$this->documentManager
			->expects( $this->once() )
			->method('persist')
			->with( $this->isInstanceOf( AccessToken::class ) );

		$this->documentManager
			->expects( $this->once() )
			->method('flush');

		$this->dispatch( self::ENDPOINT, Request::METHOD_POST, [
			'accessToken' => self::DUMMY_ACCESS_TOKEN,
			'refreshToken' => self::DUMMY_REFRESH_TOKEN
		] );
		$this->assertControllerIs( RefreshController::class );
		$this->assertResponseStatusCode(201);

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

	public function testMethodsNotAllowed () {
		$this->assertOtherMethodsNotAllowed( self::ENDPOINT, [Request::METHOD_POST] );
	}
}