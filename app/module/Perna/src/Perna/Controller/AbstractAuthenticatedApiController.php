<?php

namespace Perna\Controller;

use Perna\Service\AuthenticationService;
use Zend\Http\Request as HttpRequest;
use Zend\Mvc\MvcEvent;
use ZfrRest\Http\Exception\Client\UnauthorizedException;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;

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

	/**
	 * Throws an Exception when no Access-Token has been provided
	 * @throws    UnprocessableEntityException  If access token has not been sent in request
	 */
	protected function assertAccessToken () {
		if ( $this->accessToken == null )
			throw new UnauthorizedException("You must provide an Access-Token header with your current access token.");
	}
}