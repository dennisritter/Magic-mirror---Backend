<?php

namespace Perna\Test\Controller\User;

use Perna\Document\AccessToken;
use Zend\Http\Request;

class LogoutControllerTest extends AbstractUserControllerTestCase {
	
	const ENDPOINT = '/v1/logout';

	public function setUp() {
		parent::setUp();

		$this->documentManager
			->method('getRepository')
			->with( $this->equalTo( AccessToken::class ) )
			->willReturn( $this->documentRepository );
	}

	public function testSuccess () {
		$at = new AccessToken();

		$this->documentManager
			->expects( $this->once() )
			->method('getRepository');

		$this->documentRepository
			->expects( $this->once() )
			->method('find')
			->with( $this->equalTo( self::DUMMY_ACCESS_TOKEN ) )
			->willReturn( $at );

		$this->documentManager
			->expects( $this->once() )
			->method('remove')
			->with( $this->equalTo( $at ) );


		$this->documentManager
			->expects( $this->once() )
			->method('flush');

		$this->setRequestHeaderLine('Access-Token', self::DUMMY_ACCESS_TOKEN);
		$this->dispatch( self::ENDPOINT, Request::METHOD_POST );
		$this->assertResponseStatusCode(201);
	}

	public function testAccessTokenMissingError () {
		$this->documentManager
			->expects( $this->never() )
			->method('getRepository');

		$this->documentManager
			->expects( $this->never() )
			->method('remove');

		$this->documentManager
			->expects( $this->never() )
			->method('flush');

		$this->documentRepository
			->expects( $this->never() )
			->method('find');

		$this->dispatch( self::ENDPOINT, Request::METHOD_POST );
		$this->getErrorResponseContent( 401 );
	}
}