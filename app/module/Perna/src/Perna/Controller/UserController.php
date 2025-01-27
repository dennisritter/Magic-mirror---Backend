<?php

namespace Perna\Controller;

use Perna\Hydrator\UserHydrator;
use Perna\InputFilter\UserPutInputFilter;
use Swagger\Annotations as SWG;

class UserController extends AbstractUserController {
	
	/**
	 * @SWG\Put(
	 *    path="/user",
	 *    summary="Update current user",
	 *    operationId="updateUser",
	 *	  tags={"user"},
	 *    @SWG\Parameter(
	 *      name="data",
	 *      in="body",
	 *      description="The user data as JSON object",
	 *      required=true,
	 *      @SWG\Schema(
	 *        @SWG\Property(property="email", type="string"),
	 *        @SWG\Property(property="firstName", type="string"),
	 *        @SWG\Property(property="lastName", type="string"),
	 *        @SWG\Property(property="password", type="string")
	 *      )
	 *    ),
	 *    @SWG\Parameter(ref="#/parameters/accessToken"),
	 *    @SWG\Response(
	 *		  response="200",
	 *		  description="User was successfully updated.",
	 *		  @SWG\Schema(
	 *        @SWG\Property(property="success", type="boolean", default=true),
	 *        @SWG\Property(property="data", ref="User", description="The updated user data.")
	 *      )
	 *	  ),
	 *    @SWG\Response(response="422", ref="#/responses/422"),
	 *    @SWG\Response(response="403", ref="#/responses/403")
	 * )
	 */
	public function put () {
		$this->assertAccessToken();
		$data = $this->validateIncomingData( UserPutInputFilter::class );
		$user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
		$this->hydrateObject( UserHydrator::class, $user, $data );
		$password = $data["password"] ?? null;
		$this->userService->update( $user, $password );
		return $this->createDefaultViewModel( $this->userHydrator->extract( $user ) );
	}

	/**
	 * @SWG\Get(
	 *    path="/user",
	 *    summary="Get current user",
	 *    operationId="getUser",
	 *	  tags={"user"},
	 *    @SWG\Property(ref="#/properties/accessToken"),
	 *    @SWG\Response(
	 *		  response="200",
	 *		  description="Get current logged in user.",
	 *		  @SWG\Schema(
	 *        @SWG\Property(property="success", type="boolean", default=true),
	 *        @SWG\Property(property="data", ref="User", description="The user data")
	 *      )
	 *	  ),
	 *    @SWG\Response(response="403", ref="#/responses/403")
	 * )
	 */
	public function get() {
		$this->assertAccessToken();
		$user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
		return $this->createDefaultViewModel( $this->userHydrator->extract( $user ) );
	}
}