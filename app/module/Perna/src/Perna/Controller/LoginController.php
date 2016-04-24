<?php

namespace Perna\Controller;
use Perna\Hydrator\UserTokenHydrator;
use Perna\InputFilter\LoginCredentialsInputFilter;

/**
 * Controller for Login action
 *
 * @author      Jannik Portz
 * @package     Perna\Controller
 */
class LoginController extends AbstractAuthenticatedApiController {

	public function post () {
		$credentials = $this->validateIncomingData( LoginCredentialsInputFilter::class );
		$token = $this->authenticationService->loginUser( $credentials['email'], $credentials['password'] );
		return $this->createDefaultViewModel( $this->extractObject( UserTokenHydrator::class, $token ) );
	}
}