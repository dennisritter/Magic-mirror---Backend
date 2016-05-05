<?php

namespace Perna\Controller\GoogleAuth;

use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Service\AuthenticationService;
use Perna\Service\GoogleAuthenticationService;
use Swagger\Annotations as SWG;

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

	/**
	 * @SWG\Get(
	 *   path="/google-auth/auth-url",
	 *   summary="Creates Google Auth URL",
	 *   description="Creates a URL for the current user to permit Google OAuth",
	 *   operationId="googleAuthURL",
	 *   tags={"google"},
	 *   @SWG\Parameter(
	 *    name="Access-Token",
	 *    in="header",
	 *    type="string",
	 *    description="The current access token",
	 *    required=true
	 *   ),
	 *   @SWG\Response(
	 *    response="200",
	 *    description="A Google Auth URL has been created",
	 *    @SWG\Schema(
	 *      required={"url"},
	 *      @SWG\Property(property="url", description="The Google Auth URL for the current user", type="string", format="url")
	 *    )
	 *   )
	 * )
	 */
	public function get () {
		$this->assertAccessToken();
		$user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
		$url = $this->googleAuthenticationService->generateAuthUrl( $user );
		return $this->createDefaultViewModel([
			'url' => $url
		]);
	}
}