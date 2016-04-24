<?php

namespace Perna\Controller;
use Perna\Service\AuthenticationService;

/**
 * Controller for Login action
 *
 * @author      Jannik Portz
 * @package     Perna\Controller
 */
class LoginController extends AbstractApiController {
	
	/**
	 * @var       AuthenticationService
	 */
	protected $authenticationService;

	public function __construct ( AuthenticationService $authenticationService ) {
		$this->authenticationService;
	}

	public function post () {
		
	}
}