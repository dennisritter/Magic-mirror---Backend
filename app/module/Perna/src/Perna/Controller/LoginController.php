<?php

namespace Perna\Controller;
use Perna\Hydrator\UserTokenHydrator;
use Perna\InputFilter\LoginCredentialsInputFilter;
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
		$credentials = $this->validateIncomingData( LoginCredentialsInputFilter::class );
		$token = $this->authenticationService->loginUser( $credentials['email'], $credentials['password'] );
		return $this->createDefaultViewModel( $this->extractObject( UserTokenHydrator::class, $token ) );
	}
}