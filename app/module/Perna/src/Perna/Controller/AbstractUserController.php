<?php

namespace Perna\Controller;

use Perna\Hydrator\UserHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\UserService;
use Swagger\Annotations as SWG;

class AbstractUserController extends AbstractAuthenticatedApiController {

	protected $userService;

	protected $userHydrator;
	
	public function __construct( AuthenticationService $authenticationService,UserService $userService, UserHydrator $userHydrator ) {
		parent::__construct( $authenticationService );
		$this->userService = $userService;
		$this->userHydrator = $userHydrator;
	}
}