<?php

namespace Perna\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Google_Client;
use Google_Service_Calendar;
use Perna\Document\GoogleAccessToken;
use Perna\Document\GoogleAuthStateToken;
use Perna\Document\User;
use Perna\Hydrator\GoogleAccessTokenHydrator;
use ZfrRest\Http\Exception\Client\ForbiddenException;
use ZfrRest\Http\Exception\Client\UnauthorizedException;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;
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
	const CLIENT_ID = '1004755994493-vn9p9404kf3gie85k8ehjise9duuemct.apps.googleusercontent.com';
	const CLIENT_SECRET = 'H_s_WxHQv_ciVafulFpX_E6e';
	const CLIENT_ACCESS_TYPE = 'offline';
	const CLIENT_REDIRECT_URI = 'http://api.perna.dev/v1/google-auth/callback';

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
	 * @return    array               Array with 'auth_url' and 'state'
	 */
	public function generateAuthUrl ( User $user ) : array {
		/** @var \Google_Auth_OAuth2 $auth */
		$client = $this->createUnauthorizedClient();

		$stateToken = new GoogleAuthStateToken();
		$stateToken->setToken( $this->guidGenerator->generateGUID() );
		$stateToken->setUser( $user );

		$this->documentManager->persist( $stateToken );
		$this->documentManager->flush();

		$auth = $client->getAuth();
		$auth->setState( $stateToken->getToken() );

		return [
			'url' => $client->createAuthUrl(),
			'state' => $stateToken->getToken()
		];
	}

	/**
	 * Authenticates a User identified by a state
	 * @param     string    $state    The state token to use
	 * @param     string    $authCode The Google Auth Code
	 * @return    User                The authenticated User
	 *
	 * @throws UnauthorizedException        If the state token could not be found
	 * @throws InternalServerErrorException If an unknown error occurred while authenticating
	 */
	public function authenticateByState ( string $state, string $authCode ) : User {
		$stateToken = $this->documentManager->getRepository( GoogleAuthStateToken::class )->find( $state );
		if ( !$stateToken instanceof GoogleAuthStateToken )
			throw new UnauthorizedException("The specified state token does not exist.");

		try {
			$user = $this->authenticateUser( $stateToken->getUser(), $authCode );

			$this->documentManager->remove( $stateToken );
			$this->documentManager->flush();

			return $user;
		} catch ( \Google_Auth_Exception $e ) {
			error_log("Google Authentication Error: " . $e->getTraceAsString() );
			throw new InternalServerErrorException("Error while authenticating Google user.");
		}
	}

	/**
	 * Authenticates the specified user with the Google Auth Code
	 * @param     User      $user     The User to authenticate
	 * @param     string    $authCode The Google Auth Code
	 * @return    User                The authenticated User
	 *
	 * @throws    \Google_Auth_Exception
	 */
	public function authenticateUser ( User $user, string $authCode ) : User {
		$client = $this->createUnauthorizedClient();
		$accessTokenData = $client->authenticate( $authCode );
		$token = $this->tokenHydrator->hydrateFromJson( $accessTokenData, $user->getGoogleAccessToken() ?? new GoogleAccessToken() );
		$user->setGoogleAccessToken( $token );
		return $user;
	}

	/**
	 * Creates an Authorized Client for the specified User
	 * @param     User      $user     The User for which to create the Google Client
	 * @return    Google_Client       The authenticated Client for the User
	 *
	 * @throws    ForbiddenException            If the user has not yet authenticated at Google or access has been revoked by the user
	 * @throws    InternalServerErrorException  If something went wrong while refreshing the access token
	 */
	public function createAuthorizedClient ( User $user ) {
		$client = $this->createUnauthorizedClient();
		$token = $user->getGoogleAccessToken();

		if ( !$token instanceof GoogleAccessToken )
			throw new ForbiddenException("The user has not yet authenticated at Google.");

		$tokenData = $this->tokenHydrator->extractToJson( $token );
		$client->setAccessToken( $tokenData );

		if ( $client->isAccessTokenExpired() ) {
			try {
				$client->refreshToken( $client->getRefreshToken() );
			} catch ( \Google_Auth_Exception $e ) {
				if ( strpos( $e->getMessage(), 'revoked' ) > -1 ) {
					throw new ForbiddenException("The Google Calendar access has been revoked by the user.");
				}

				error_log( 'Google Refresh Error: ' . $e->getTraceAsString() );
				throw new InternalServerErrorException("An error occurred while refreshing the Google Access Token.");
			}

			$this->tokenHydrator->hydrateFromJson( $client->getAccessToken(), $token );
		}

		return $client;
	}
}