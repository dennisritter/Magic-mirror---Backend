<?php

namespace Perna\Test\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Perna\Controller\RegisterController;
use Perna\Document\User;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;

class RegisterControllerTest extends AbstractControllerTestCase {

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $documentManager;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $documentRepository;

	public function setUp () {
		parent::setUp();

		$repoMock = $this->getMockBuilder( DocumentRepository::class )->disableOriginalConstructor()->getMock();

		$dmMock = $this->getMockBuilder( DocumentManager::class )->disableOriginalConstructor()->getMock();
		$dmMock->expects( $this->once() )->method('getRepository')
		       ->with( $this->equalTo( User::class ) )
		       ->willReturn( $repoMock );

		$this->documentManager = $dmMock;
		$this->documentRepository = $repoMock;

		$sm = $this->getApplicationServiceLocator();
		$sm->setAllowOverride( true );
		$sm->setService( DocumentManager::class, $dmMock );
	}
	
	public function testRegistrationSuccess () {
		$requestData = [
			'email' => 'meine@emailadresse.de',
			'firstName' => 'Jannik',
			'lastName' => 'Portz',
			'password' => 'auvgqovgrovo'
		];

		$this->documentRepository
			->expects( $this->once() )
			->method('findBy')
			->with( $this->equalTo( ['email' => $requestData['email']] ) );

		$this->documentManager
			->expects( $this->once() )
			->method('flush');

		$this->documentManager
			->expects( $this->once() )
			->method('persist')
			->with( $this->isInstanceOf( User::class ) );

		$this->dispatch( '/v1/register', 'POST', $requestData );
		$this->assertControllerIs( RegisterController::class );

		$this->assertResponseStatusCode( 201 );
		$data = $this->getSuccessResponseData();

		unset( $requestData['password'] );
		$this->assertArraySubset( $requestData, $data );
		$this->assertArrayHasKey( 'lastLogin', $data );
		$this->assertNull( $data['lastLogin'] );
	}

	public function testUserAlreadyExists () {
		$requestData = [
			'email' => 'meine@emailadresse.de',
			'firstName' => 'Jannik',
			'lastName' => 'Portz',
			'password' => 'dgslfmmbth'
		];

		$this->documentRepository
			->expects( $this->once() )
			->method('findBy')
			->with( $this->equalTo(['email' => $requestData['email']]) )
			->willReturn( [ new User() ] );

		$this->documentManager->expects( $this->never() )->method('persist');
		$this->documentManager->expects( $this->never() )->method('flush');

		$this->dispatch( '/v1/register', 'POST', $requestData );
		$this->assertControllerIs( RegisterController::class );
		
		$this->assertResponseStatusCode( 422 );
		$this->getErrorResponseContent( 422 );
	}
}