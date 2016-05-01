<?php

namespace Perna\Controller;

use Perna\Hydrator\AccessTokenHydrator;
use Perna\InputFilter\RefreshInputFilter;

/**
 * Controller for refreshing an AccessToken
 *
 * @author      Jannik Portz
 * @package     Perna\Controller
 */
class RefreshController extends AbstractAuthenticatedApiController {

	public function post () {
		$data = $this->validateIncomingData( RefreshInputFilter::class );
		$token = $this->authenticationService->refreshToken( $data['accessToken'], $data['refreshToken'] );
		return $this->createDefaultViewModel( $this->extractObject( AccessTokenHydrator::class, $token ) );
	}
}