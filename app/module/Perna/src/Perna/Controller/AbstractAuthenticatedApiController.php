<?php

namespace Perna\Controller;
use Perna\Service\AuthenticationService;
use Zend\Http\Request as HttpRequest;
use Zend\Mvc\MvcEvent;

/**
 * Abstraction for all controllers that require Authentication
 *
 * @author      Jannik Portz
 * @package     Perna\Controller
 */
class AbstractAuthenticatedApiController extends AbstractApiController {

	const ACCESS_TOKEN_HEADER = 'Access-Token';

	/**
	 * The AuthenticationService to use for Authentication
	 * @var       AuthenticationService
	 */
	protected $authenticationService;

	/**
	 * The access token that has been passed in the Request
	 * @var       string
	 */
	protected $accessToken;

	public function __construct ( AuthenticationService $authenticationService ) {
		$this->authenticationService = $authenticationService;
		$this->accessToken = null;
	}

	/**
	 * Sets the accessToken attribute value if an Access Token Header has been passed in the Request
	 * @inheritdoc
	 */
	public function onDispatch ( MvcEvent $event ) {
		$request = $event->getRequest();

		if ( $request instanceof HttpRequest && $request->getHeaders()->has( self::ACCESS_TOKEN_HEADER ) ) {
			$header = $request->getHeaders()->get( self::ACCESS_TOKEN_HEADER );
			$this->accessToken = trim( $header->getFieldValue() );
		}
		
		parent::onDispatch( $event );
	}
}