<?php

namespace Perna\Controller;

use Perna\Hydrator\UserTokenHydrator;
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
	 *      @SWG\Property(property="password", type="string", description="The password of the user in plain text")
	 *    )
	 *  ),
	 *  @SWG\Response(
	 *    response="200",
	 *    description="The specified credentials are valid and a new AccessToken has been created.",
	 *    @SWG\Schema(ref="AccessToken")
	 *  ),
	 *  @SWG\Response(response="422", description="The specified credentials are invalid. No AccessToken has been created.")
	 * )
	 */
	public function post () {
		$credentials = $this->validateIncomingData( LoginCredentialsInputFilter::class );
		$token = $this->authenticationService->loginUser( $credentials['email'], $credentials['password'] );
		return $this->createDefaultViewModel( $this->extractObject( UserTokenHydrator::class, $token ) );
	}
}