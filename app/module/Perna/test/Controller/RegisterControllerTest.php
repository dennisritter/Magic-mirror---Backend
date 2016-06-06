<?php

namespace Perna\Test\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Perna\Controller\RegisterController;
use Perna\Document\User;

class RegisterControllerTest extends AbstractControllerTestCase {

	const ENDPOINT = '/v1/register';

	const DUMMY_DATA = [
		'email' => 'meine@emailadresse.de',
		'firstName' => 'Jannik',
		'lastName' => 'Portz',
		'password' => 'vkwovbwfvw'
	];

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $documentManager;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $documentRepository;

	public function setUp () {
		parent::setUp();

		$repoMock = $this->getMockBuilder( DocumentRepository::class )->disableOriginalConstructor()->getMock();

		$dmMock = $this->getMockBuilder( DocumentManager::class )->disableOriginalConstructor()->getMock();
		$dmMock->method('getRepository')
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

	protected function abstractValidationTest ( array $requestData, bool $success, string $property = '' ) {
		foreach ( ['getRepository', 'persist', 'flush'] as $m )
			$this->documentManager->expects( $this->never() )->method( $m );

		$this->dispatch( self::ENDPOINT, 'POST', $requestData );
		$this->assertControllerIs( RegisterController::class );

		if ( !$success ) {
			$this->assertResponseStatusCode( 422 );
			$data = $this->getErrorResponseContent( 422 );
			$this->assertArrayHasKey('message', $data);
			$this->assertArrayHasKey('errors', $data);
			$this->assertTrue( is_array($data['errors']) );
			$this->assertArrayHasKey($property, $data['errors']);
			$this->assertTrue( is_array( $data['errors'][$property] ) );
			$this->assertGreaterThanOrEqual( 1, count( $data['errors'][$property] ) );
		} else {
			$this->assertResponseStatusCode( 201 );
			$data = $this->getSuccessResponseData();
			unset( $data['login'] );
			$this->assertArraySubset( $data, $requestData );
		}
	}

	public function testFirstNameMissing () {
		$d = self::DUMMY_DATA;
		unset( $d['firstName'] );
		$this->abstractValidationTest( $d, false, 'firstName');
	}

	public function testFirstNameTooShort () {
		$this->abstractValidationTest( array_merge(self::DUMMY_DATA, [
			'firstName' => 'J'
		]), false, 'firstName' );
	}

	public function testFirstNameLongEnough () {
		$this->abstractValidationTest( array_merge(self::DUMMY_DATA, [
			'firstName' => 'Ja'
		]), true, 'firstName' );
	}

	public function testFirstNameTooLong () {
		$this->abstractValidationTest( array_merge(self::DUMMY_DATA, [
			'firstName' => 'J'
		]), false, 'firstName' );
	}
}