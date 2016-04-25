<?php

namespace Perna\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Document\User;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;

class UserService {

	protected $passwordService;

	protected $documentManager;

	public function __construct ( PasswordService $passwordService, DocumentManager $documentManager ) {
		$this->passwordService = $passwordService;
		$this->documentManager = $documentManager;
	}

	public function register ( User $user, string $password ) {
		$usersWithSameEmail = $this->documentManager->getRepository( User::class )->findBy([
			'email' => $user->getEmail()
		]);
		
		if ( count( $usersWithSameEmail ) > 0 )
			throw new UnprocessableEntityException( "A user with the email address {$user->getEmail()} already exists." );
			
		$this->passwordService->setUserPassword( $user, $password );
		$this->documentManager->persist( $user );
		$this->documentManager->flush();
	}
	
	public function update ( User $user, string $password = null ) {
		if ( $password != null )
			$this->passwordService->setUserPassword( $user, $password );
		$this->documentManager->flush();
	}

	public function getUserByEmail ( string $email ) : User {
		return $this->documentManager->getRepository(User::class)->findOneBy( ["email" => $email] );
	}
}