<?php

namespace Perna\Test\Controller\User;

use Perna\Controller\RegisterController;
use Perna\Document\User;
use Zend\Http\Request;

class RegisterControllerTest extends AbstractUserControllerTestCase {

	const ENDPOINT = '/v1/register';

	const DUMMY_DATA = [
		'email' => 'meine@emailadresse.de',
		'firstName' => 'Jannik',
		'lastName' => 'Portz',
		'password' => 'vkwovbwfvw'
	];

	public function testRegistrationSuccess () {
		$requestData = self::DUMMY_DATA;

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

		$this->dispatch( self::ENDPOINT, Request::METHOD_POST, $requestData );
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

		$this->dispatch( self::ENDPOINT, 'POST', $requestData );
		$this->assertControllerIs( RegisterController::class );
		
		$this->assertResponseStatusCode( 422 );
		$this->getErrorResponseContent( 422 );
	}

	protected function abstractValidationTest ( array $requestData, bool $success, string $property = '' ) {
		if ( $success ) {
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
		} else {
			foreach ( ['getRepository', 'persist', 'flush'] as $m )
				$this->documentManager->expects( $this->never() )->method( $m );
		}

		$this->dispatch( self::ENDPOINT, 'POST', $requestData );
		$this->assertControllerIs( RegisterController::class );

		if ( $success ) {
			$this->assertResponseStatusCode( 201 );
			$data = $this->getSuccessResponseData();
			unset( $data['lastLogin'] );
			$this->assertArraySubset( $data, $requestData );
		} else {
			$this->assertResponseStatusCode( 422 );
			$data = $this->getErrorResponseContent( 422 );
			$this->assertArrayHasKey( 'message', $data );
			$this->assertArrayHasKey( 'errors', $data );
			$this->assertTrue( is_array( $data['errors'] ) );
			$this->assertArrayHasKey( $property, $data['errors'] );
			$this->assertTrue( is_array( $data['errors'][ $property ] ) );
			$this->assertGreaterThanOrEqual( 1, count( $data['errors'][ $property ] ) );
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
			'firstName' => $this->randomString(101)
		]), false, 'firstName' );
	}

	public function testFirstNameShortEnough () {
		$this->abstractValidationTest( array_merge(self::DUMMY_DATA, [
			'firstName' => $this->randomString(100)
		]), true, 'firstName' );
	}

	public function testLastNameMissing () {
		$d = self::DUMMY_DATA;
		unset( $d['lastName'] );
		$this->abstractValidationTest( $d, false, 'lastName');
	}

	public function testLastNameTooShort () {
		$this->abstractValidationTest( array_merge(self::DUMMY_DATA, [
			'lastName' => 'J'
		]), false, 'lastName' );
	}

	public function testLastNameLongEnough () {
		$this->abstractValidationTest( array_merge(self::DUMMY_DATA, [
			'lastName' => 'Ja'
		]), true, 'lastName' );
	}

	public function testLastNameTooLong () {
		$this->abstractValidationTest( array_merge(self::DUMMY_DATA, [
			'lastName' => $this->randomString(101)
		]), false, 'lastName' );
	}

	public function testLastNameShortEnough () {
		$this->abstractValidationTest( array_merge(self::DUMMY_DATA, [
			'lastName' => $this->randomString(100)
		]), true, 'lastName' );
	}

	public function testEmailInvalid () {
		$this->abstractValidationTest( array_merge(self::DUMMY_DATA, [
			'email' => 'no-email-address'
		]), false, 'email' );
	}

}