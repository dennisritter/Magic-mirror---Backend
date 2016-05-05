<?php

namespace Perna\Controller\GoogleAuth;

use Perna\Controller\AbstractApiController;
use Perna\Service\GoogleAuthenticationService;
use Zend\Http\Request;
use ZfrRest\Http\Exception\Client\UnauthorizedException;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;

/**
 * Controller for Google Auth Callback action
 *
 * @author      Jannik Portz
 * @package     Perna\Controller\GoogleAuth
 */
class CallbackController extends AbstractApiController {

	/**
	 * @var       GoogleAuthenticationService
	 */
	protected $googleAuthenticationService;

	public function __construct( GoogleAuthenticationService $googleAuthenticationService ) {
		$this->googleAuthenticationService = $googleAuthenticationService;
	}

	public function get () {
		/** @var Request $request */
		$request = $this->getRequest();
		$code = $request->getQuery('code', null);
		$error = $request->getQuery('error', null);
		$state = $request->getQuery('state', null);

		if ( $state === null )
			throw new UnprocessableEntityException("No state has been provided.");

		if ( $error !== null )
			throw new UnauthorizedException("Access has been denied by the Google user.");

		if ( $code === null )
			throw new UnprocessableEntityException("No code has been provided.");

		$this->googleAuthenticationService->authenticateByState( $state, $code );

		return $this->createDefaultViewModel([
			'success' => true
		]);
	}
}