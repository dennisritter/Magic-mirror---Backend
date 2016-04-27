<?php

namespace Perna\Controller;

use Swagger\Annotations as SWG;

/**
 * Controller for Logout action
 *
 * @author      Jannik Portz
 * @package     Perna\Controller
 */
class LogoutController extends AbstractAuthenticatedApiController {

	/**
	 * @SWG\Post(
	 *   path="/logout",
	 *   summary="Logout-Endpoint",
	 *   description="Deletes the access token specified in the Request Header",
	 *   operationId="logout",
	 *   tags={"user"},
	 *   @SWG\Parameter(
	 *    in="header",
	 *    name="Access-Token",
	 *    type="string",
	 *    description="The current access token",
	 *    required=true
	 *   ),
	 *   @SWG\Response(response="200", description="The specified access token has been deleted.")
	 * )
	 */
	public function post () {
		$this->assertAccessToken();
		$this->authenticationService->logoutUser( $this->accessToken );
		return $this->createDefaultViewModel( null );
	}
}