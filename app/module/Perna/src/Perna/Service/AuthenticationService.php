<?php

namespace Perna\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Document\AccessToken;
use Perna\Document\User;
use DateTime;
use DateInterval;
use ZfrRest\Http\Exception\Client\UnauthorizedException;

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

	public function __construct ( DocumentManager $documentManager, GUIDGenerator $guidGenerator ) {
		$this->documentManager = $documentManager;
		$this->guidGenerator = $guidGenerator;
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