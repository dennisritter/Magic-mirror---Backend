<?php

namespace Perna\Controller\GoogleAuth;

use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Service\AuthenticationService;
use Perna\Service\GoogleAuthenticationService;

/**
 * Class AuthUrlController
 *
 * @author      Jannik Portz
 * @package     Perna\Controller\GoogleAuth
 */
class AuthUrlController extends AbstractAuthenticatedApiController {

	/**
	 * @var       GoogleAuthenticationService
	 */
	protected $googleAuthenticationService;

	public function __construct( AuthenticationService $authenticationService, GoogleAuthenticationService $googleAuthenticationService ) {
		parent::__construct( $authenticationService );
		$this->googleAuthenticationService = $googleAuthenticationService;
	}

	public function get () {
		$this->assertAccessToken();
		$user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
		$url = $this->googleAuthenticationService->generateAuthUrl( $user );
		return $this->createDefaultViewModel([
			'url' => $url
		]);
	}
}