<?php

namespace Perna\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Document\User;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;

/**
 * Service for User-Operations
 *
 * @author      Jannik Portz
 * @package     Perna\Service
 */
class UserService {

	/**
	 * @var PasswordService
	 */
	protected $passwordService;

	/**
	 * @var DocumentManager
	 */
	protected $documentManager;

	public function __construct ( PasswordService $passwordService, DocumentManager $documentManager ) {
		$this->passwordService = $passwordService;
		$this->documentManager = $documentManager;
	}

	/**
	 * Registers a new User
	 * @param     User      $user     Object representation of the User to register
	 * @param     string    $password The password for the new user
	 *
	 * @throws    UnprocessableEntityException  If a user with the same email address already exists
	 */
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

	/**
	 * Updates the User in the database
	 * @param     User      $user     The User to update with all new data set
	 * @param     string    $password The new password for the user.
	 *                                When null, the password won't be changed.
	 */
	public function update ( User $user, string $password = null ) {
		if ( $password != null )
			$this->passwordService->setUserPassword( $user, $password );
		$this->documentManager->flush();
	}

	public function getUserByEmail ( string $email ) : User {
		return $this->documentManager->getRepository(User::class)->findOneBy( ["email" => $email] );
	}
}