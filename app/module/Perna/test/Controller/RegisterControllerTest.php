<?php

namespace Perna\Test\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Perna\Controller\RegisterController;
use Perna\Document\User;
use Perna\Test\ApiTest\Bootstrap;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class RegisterControllerTest extends AbstractHttpControllerTestCase {

	public function testRegistrationSuccess () {
		$requestData = [
			'email' => 'meine@emailadresse.de',
			'firstName' => 'Jannik',
			'lastName' => 'Portz',
			'password' => 'auvgqovgrovo'
		];

		$repoMock = $this->getMockBuilder( DocumentRepository::class )->disableOriginalConstructor()->getMock();
		$repoMock->expects( $this->once() )->method('findBy')
			->with( $this->equalTo( ['email' => $requestData['email'] ] ) )
			->willReturn( [] );

		$dmMock = $this->getMockBuilder( DocumentManager::class )->disableOriginalConstructor()->getMock();
		$dmMock->expects( $this->once() )->method('getRepository')
			->with( $this->equalTo( User::class ) )
			->willReturn( $repoMock );

		$dmMock->expects( $this->once() )->method('persist')
			->with( $this->isInstanceOf( User::class ) );

		$dmMock->expects( $this->once() )->method('flush');

		$sm = Bootstrap::getServiceManager();
		$sm->setAllowOverride( true );
		$sm->setService( DocumentManager::class, $dmMock );

		$this->dispatch( '/v1/register', 'POST', $requestData );
		$this->assertControllerName( RegisterController::class );
		$this->assertControllerClass( RegisterController::class );
		$this->assertResponseStatusCode( 200 );
		$response = $this->getResponse();
		$content = json_decode( trim( $response->getContent() ) );

		$this->assertTrue( is_array( $content ) );
		$this->assertArrayHasKey( 'success', $content );
		$this->assertTrue( $content['success'] );
		$this->assertArrayHasKey('data', $content);

		$data = $response['data'];
		unset( $requestData['password'] );
		$this->assertEquals( $requestData, $data );
	}
}