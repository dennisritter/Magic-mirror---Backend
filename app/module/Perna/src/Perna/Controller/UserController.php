<?php

namespace Perna\Controller;

use Perna\Document\User;
use Perna\Hydrator\UserHydrator;
use Perna\InputFilter\UserInputFilter;
use Perna\Service\UserService;
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
	
	public function put () {
		$data = $this->validateIncomingData( UserInputFilter::REQUIRED_PASSWORD );
		$user = $this->userService->getUserByEmail( $data["email"] );
		$this->hydrateObject( UserHydrator::class, $user, $data );
		$this->userService->update( $user, $data['password'] );
		return $this->createDefaultViewModel( $this->userHydrator->extract( $user ) );
	}

	//todo get()- Methode implementieren
}