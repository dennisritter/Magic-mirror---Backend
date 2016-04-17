<?php

namespace Perna\Api;

use Swagger\Annotations as SWG;

class UserController {

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
	public function post () {}

}