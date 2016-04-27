<?php

namespace Perna\Service;

use DateInterval;
use DateTime;
use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Document\AccessToken;
use Perna\Document\User;
use ZfrRest\Http\Exception\Client\UnauthorizedException;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;

/**
 * Service responsible for Authentication
 *
 * @author      Jannik Portz
 * @package     Perna\Service
 */
class AuthenticationService {

	/**
	 * @var       DocumentManager
	 */
	protected $documentManager;

	/**
	 * @var       GUIDGenerator
	 */
	protected $guidGenerator;

	/**
	 * @var       PasswordService
	 */
	protected $passwordService;

	public function __construct ( DocumentManager $documentManager, GUIDGenerator $guidGenerator, PasswordService $passwordService ) {
		$this->documentManager = $documentManager;
		$this->guidGenerator = $guidGenerator;
		$this->passwordService = $passwordService;
	}

	/**
	 * Generates an AccessToken for the specified User
	 * @param     User      $user     The user for whom to create the AccessToken
	 * @return    AccessToken         The newly created AccessToken for the User
	 */
	public function generateAccessToken ( User $user ) : AccessToken {
		$token = new AccessToken();
		$token->setToken( $this->guidGenerator->generateGUID() );
		$token->setUser( $user );
		$token->setExpires( true );

		$expiration = new DateTime();
		$expiration->add( new DateInterval('P1D') );
		$token->setExpirationDate( $expiration );

		$this->documentManager->persist( $token );

		return $token;
	}

	/**
	 * Generates a new AccessToken for the User identified by the credentials
	 *
	 * @param     string    $email    The user's email
	 * @param     string    $password The user's password
	 * @return    AccessToken         The newly created AccessToken for the user
	 *
	 * @throws    UnprocessableEntityException  If no user could be found by the specified email address
	 *                                          If the password is incorrect
	 */
	public function loginUser ( string $email, string $password ) : AccessToken {
		$user = $this->documentManager->getRepository( User::class )->findOneBy([
			'email' => $email
		]);

		if ( !$user instanceof User )
			throw new UnprocessableEntityException("A user with the email address {$email} could not be found.");

		if ( !$this->passwordService->passwordMatches( $user, $password ) )
			throw new UnprocessableEntityException("The specified password is incorrect.");

		$token = $this->generateAccessToken( $user );
		$user->setLastLogin( new DateTime('now') );

		$this->documentManager->flush();
		return $token;
	}

	/**
	 * Removes the access token
	 *
	 * @param     string    $token    The AccessToken to remove
	 *
	 * @throws    UnprocessableEntityException  When the access token could not be found
	 */
	public function logoutUser ( string $token ) {
		$token = $this->documentManager->getRepository( AccessToken::class )->find( $token );

		if ( !$token instanceof AccessToken )
			return;

		$this->documentManager->remove( $token );
		$this->documentManager->flush();
	}

	/**
	 * Tries to find the User with the valid access token
	 * @param     string    $token    The requested access token as string
	 * @return    User                The user for the valid access token
	 *
	 * @throws    UnauthorizedException If no user could be authenticated by the provided access token
	 */
	public function findAuthenticatedUser ( string $token ) : User {
		$token = $this->documentManager->getRepository( AccessToken::class )->find( $token );

		if ( !$token instanceof AccessToken )
			throw new UnauthorizedException( "The provided access token could not be found." );
		
		if ( $token->getExpires() ) {
			$now = new DateTime();
			if ( $token->getExpirationDate() < $now )
				throw new UnauthorizedException( "The provided access token has already expired." );
		}

		$user = $token->getUser();
		if ( !$user instanceof User )
			throw new UnauthorizedException( "No user could be mapped to the access token." );

		return $user;
	}
}