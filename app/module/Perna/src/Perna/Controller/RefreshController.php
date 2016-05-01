<?php

namespace Perna\Controller;

use Perna\Hydrator\AccessTokenHydrator;
use Perna\InputFilter\RefreshInputFilter;
use Swagger\Annotations as SWG;

/**
 * Controller for refreshing an AccessToken
 *
 * @author      Jannik Portz
 * @package     Perna\Controller
 */
class RefreshController extends AbstractAuthenticatedApiController {

	/**
	 * @SWG\Post(
	 *   path="/refresh",
	 *   summary="Refresh-Endpoint",
	 *   description="Refreshes an old access token with a refresh token",
	 *   operationId="refreshAccessToken",
	 *   tags={"user"},
	 *   @SWG\Parameter(
	 *    in="body",
	 *    name="body",
	 *    required=true,
	 *    @SWG\Schema(
	 *      required={"accessToken", "refreshToken"},
	 *      @SWG\Property(property="accessToken", type="string", description="The old access token"),
	 *      @SWG\Property(property="refreshToken", type="string", description="The corresponding refresh token")
	 *    )
	 *   ),
	 *   @SWG\Response(
	 *    response="201",
	 *    description="New access and refresh tokens have been generated. The old tokens are no longer valid.",
	 *    @SWG\Schema(ref="AccessToken")
	 *   ),
	 *   @SWG\Response(response="422", description="Access and/or refresh tokens are invalid.")
	 * )
	 */
	public function post () {
		$data = $this->validateIncomingData( RefreshInputFilter::class );
		$token = $this->authenticationService->refreshToken( $data['accessToken'], $data['refreshToken'] );
		return $this->createDefaultViewModel( $this->extractObject( AccessTokenHydrator::class, $token ) );
	}
}