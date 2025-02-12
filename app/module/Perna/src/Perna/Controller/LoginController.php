<?php

namespace Perna\Controller;

use Perna\Hydrator\AccessTokenHydrator;
use Perna\InputFilter\LoginCredentialsInputFilter;
use Swagger\Annotations as SWG;

/**
 * Controller for Login action
 *
 * @author      Jannik Portz
 * @package     Perna\Controller
 */
class LoginController extends AbstractAuthenticatedApiController {

	/**
	 * @SWG\Post(
	 *   path="/login",
	 *   summary="Login-Endpoint",
	 *   description="The Login-Endpoint verifies the specified credentials and creates a new AccessToken for further Authentication",
	 *   operationId="logIn",
	 *   tags={"user"},
	 *   @SWG\Parameter(
	 *    in="body",
	 *    name="body",
	 *    description="The login credentials",
	 *    required=true,
	 *    @SWG\Schema(
	 *      @SWG\Property(property="email", type="string", description="The email address of the user"),
	 *      @SWG\Property(property="password", type="string", format="password", description="The password of the user in plain text")
	 *    )
	 *  ),
	 *  @SWG\Response(
	 *    response="200",
	 *    description="The specified credentials are valid and a new access token has been created. The access token will be valid for 24 hours.",
	 *    @SWG\Schema(
	 *      @SWG\Property(property="success", type="boolean", default=true),
	 *      @SWG\Property(property="data", description="The access token", ref="AccessToken")
	 *    )
	 *  ),
	 *  @SWG\Response(response="422", ref="#/responses/422")
	 * )
	 */
	public function post () {
		$credentials = $this->validateIncomingData( LoginCredentialsInputFilter::class );
		$token = $this->authenticationService->loginUser( $credentials['email'], $credentials['password'] );
		return $this->createDefaultViewModel( $this->extractObject( AccessTokenHydrator::class, $token ) );
	}
}