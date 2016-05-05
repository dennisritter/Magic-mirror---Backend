<?php

namespace Perna\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Google_Client;
use Google_Service_Calendar;
use Perna\Document\GoogleAccessToken;
use Perna\Document\GoogleAuthStateToken;
use Perna\Document\User;
use Perna\Hydrator\GoogleAccessTokenHydrator;
use ZfrRest\Http\Exception\Client\UnauthorizedException;
use ZfrRest\Http\Exception\Server\InternalServerErrorException;

/**
 * Service dealing with OAuth2 authentication at Google
 *
 * @author      Jannik Portz
 * @package     Perna\Service
 */
class GoogleAuthenticationService {

	// TODO: move to config
	const APPLICATION_NAME = 'Perna';
	const CLIENT_ID = '60781837706-50994u7k6dviflpbu8fds3ut13j67ghm.apps.googleusercontent.com';
	const CLIENT_SECRET = 'GkJw_aresdYLvRCMgZBHvet6';
	const CLIENT_ACCESS_TYPE = 'offline';
	const CLIENT_REDIRECT_URI = 'http://api.perna.dev/google-auth/callback';

	const SCOPES = [
		Google_Service_Calendar::CALENDAR
	];

	/**
	 * The Hydrator for the GoogleAccessToken
	 * @var       GoogleAccessTokenHydrator
	 */
	protected $tokenHydrator;

	/**
	 * The DocumentManager
	 * @var       DocumentManager
	 */
	protected $documentManager;

	/**
	 * The GUID Generator
	 * @var       GUIDGenerator
	 */
	protected $guidGenerator;

	public function __construct ( GoogleAccessTokenHydrator $tokenHydrator, DocumentManager $documentManager, GUIDGenerator $GUIDGenerator ) {
		$this->tokenHydrator = $tokenHydrator;
		$this->documentManager = $documentManager;
		$this->guidGenerator = $GUIDGenerator;
	}

	/**
	 * Creates an unauthorized Google Client
	 * @return    Google_Client   The unauthorized Google Client
	 */
	public function createUnauthorizedClient () : Google_Client {
		$client = new Google_Client();
		$client->setApplicationName( self::APPLICATION_NAME );
		$client->setScopes( self::SCOPES );
		$client->setClientId( self::CLIENT_ID );
		$client->setClientSecret( self::CLIENT_SECRET );
		$client->setRedirectUri( self::CLIENT_REDIRECT_URI );
		$client->setAccessType( self::CLIENT_ACCESS_TYPE );
		return $client;
	}

	/**
	 * Generates a Google Auth URL for the specified user
	 * @param     User      $user     The user for which to generate the Auth URL
	 * @return    string              The Auth URL for the User
	 */
	public function generateAuthUrl ( User $user ) : string {
		/** @var \Google_Auth_OAuth2 $auth */
		$client = $this->createUnauthorizedClient();

		$stateToken = new GoogleAuthStateToken();
		$stateToken->setToken( $this->guidGenerator->generateGUID() );
		$stateToken->setUser( $user );

		$this->documentManager->persist( $stateToken );
		$this->documentManager->flush();

		$auth = $client->getAuth();
		$auth->setState( $stateToken->getToken() );

		return $client->createAuthUrl();
	}

	public function authenticateUser ( User $user, string $authCode ) : User {
		$client = $this->createUnauthorizedClient();
		$accessTokenData = $client->authenticate( $authCode );
		$token = $this->tokenHydrator->hydrateFromJson( $accessTokenData, new GoogleAccessToken() );
		$user->setGoogleAccessToken( $token );
		return $user;
	}

	public function createAuthorizedClient ( User $user ) {
		$client = $this->createUnauthorizedClient();
		$token = $user->getGoogleAccessToken();

		if ( !$token instanceof GoogleAccessToken )
			throw new UnauthorizedException("The user has not yet authenticated at Google.");

		$tokenData = $this->tokenHydrator->extractToJson( $token );
		$client->setAccessToken( $tokenData );

		if ( $client->isAccessTokenExpired() ) {
			try {
				$client->refreshToken( $client->getRefreshToken() );
			} catch ( \Google_Auth_Exception $e ) {
				// TODO: Better exception handling
				error_log( 'Google Refresh Error: ' . $e->getTraceAsString() );
				throw new InternalServerErrorException("An error occurred while refreshing the Google Access Token.");
			}

			$this->tokenHydrator->hydrateFromJson( $client->getAccessToken(), $token );
		}

		return $client;
	}
}