<?php

namespace Perna\Controller;

use Perna\Document\User;
use Perna\Hydrator\UserHydrator;
use Perna\InputFilter\UserInputFilter;
use Swagger\Annotations as SWG;

class RegisterController extends AbstractUserController {

	/**
	 * @SWG\Post(
	 *    path="/users",
	 *    summary="Creates a new user",
	 *    operationId="createUser",
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
	
	public function post () {
		$data = $this->validateIncomingData( UserInputFilter::class );
		$user = new User();
		$this->hydrateObject( UserHydrator::class, $user, $data );
		$this->userService->register( $user, $data['password'] );
		return $this->createDefaultViewModel( $this->userHydrator->extract( $user ) );
	}
}