<?php

namespace Perna\Controller;

use Perna\Document\User;
use Perna\Hydrator\UserHydrator;
use Perna\InputFilter\UserInputFilter;
use Perna\Service\UserService;
use Swagger\Annotations as SWG;

class AbstractUserController extends AbstractApiController {

	protected $userService;

	protected $userHydrator;
	
	public function __construct( UserService $userService, UserHydrator $userHydrator ) {
		$this->userService = $userService;
		$this->userHydrator = $userHydrator;
	}
}