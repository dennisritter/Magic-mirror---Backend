<?php

namespace Perna\Controller\GoogleAuth;

use Perna\Controller\AbstractApiController;
use Perna\Service\GoogleAuthenticationService;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;
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

		$model = new ViewModel([
			'success' => false,
			'state' => null
		]);

		$this->layout('api/layout/empty');

		$model->setTemplate('api/google-auth-callback');

		if ( $state === null ) {
			$model->setVariable('error', 'noState');
			return $model;
		}

		$model->setVariable('state', $state);

		if ( $error !== null ) {
			$model->setVariable('error', 'accessDenied');
			return $model;
		}

		if ( $code === null ) {
			$model->setVariable('error', 'noCode');
			return $model;
		}

		try {
			$this->googleAuthenticationService->authenticateByState( $state, $code );
			$model->setVariable('success', true);
		} catch ( \Exception $e ) {
			$model->setVariable('error', 'unknownError');
		}

		return $model;
	}
}