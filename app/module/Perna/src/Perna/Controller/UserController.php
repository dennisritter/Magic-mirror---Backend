<?php

namespace Perna\Controller;

use Perna\Hydrator\UserHydrator;
use Perna\InputFilter\UserPutInputFilter;
use Swagger\Annotations as SWG;

class UserController extends AbstractUserController {
	
	/**
	 * @SWG\Post(
	 *    path="/users",
	 *    summary="get and update user",
	 *    operationId="getUser",
	 *    @SWG\Parameter(
	 *      name="data",
	 *      in="body",
	 *      description="The user data",
	 *      required=true,
	 *      @SWG\Schema(
	 *        @SWG\Property(property="email", type="string"),
	 *        @SWG\Property(property="firstName", type="string"),
	 *        @SWG\Property(property="lastName", type="string"),
	 *        @SWG\Property(property="password", type="string")
	 *      )
	 *    ),
	 *    @SWG\Response(response="201", description="New user has successfully been created.")
	 * )
	 */
	// todo implement change password
	public function put () {
		$data = $this->validateIncomingData( UserPutInputFilter::class );
		$this->assertAccessToken();
		$user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
		$this->hydrateObject( UserHydrator::class, $user, $data );
		$password = $data["password"] ?? null;
		$this->userService->update( $user, $password );
		return $this->createDefaultViewModel( $this->userHydrator->extract( $user ) );
	}

	public function get() {
		$this->assertAccessToken();
		$user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
		return $this->createDefaultViewModel( $this->userHydrator->extract( $user ) );

	}
}