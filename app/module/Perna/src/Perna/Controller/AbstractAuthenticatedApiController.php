<?php

namespace Perna\Controller;
use Perna\Service\AuthenticationService;

/**
 * Abstraction for all controllers that require Authentication
 *
 * @author      Jannik Portz
 * @package     Perna\Controller
 */
class AbstractAuthenticatedApiController extends AbstractApiController {

	/**
	 * The AuthenticationService to use for Authentication
	 * @var       AuthenticationService
	 */
	protected $authenticationService;

	public function __construct ( AuthenticationService $authenticationService ) {
		$this->authenticationService = $authenticationService;
	}
}