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
	 *      required={"url", "state"},
	 *      @SWG\Property(property="url", description="The Google Auth URL for the current user", type="string", format="url"),
	 *      @SWG\Property(property="state", description="State identifier. Token for current Google Auth session.", type="string", format="GUID")
	 *    )
	 *   )
	 * )
	 */
	public function get () {
		$this->assertAccessToken();
		$user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
		$data = $this->googleAuthenticationService->generateAuthUrl( $user );
		return $this->createDefaultViewModel( $data );
	}
}