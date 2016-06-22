<?php

namespace Perna\Test\Controller\User;

use Perna\Document\AccessToken;
use Zend\Http\Request;

class LogoutControllerTest extends AbstractUserControllerTestCase {

	const DUMMY_ACCESS_TOKEN = 'C57A25FF-A9E9-5870-2E2A-6A3156EFDB22';
	const ENDPOINT = '/v1/logout';

	public function setUp() {
		parent::setUp();

		$this->documentManager
			->expects( $this->once() )
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
}