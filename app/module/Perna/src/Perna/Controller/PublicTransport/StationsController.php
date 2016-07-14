<?php

namespace Perna\Controller\PublicTransport;

use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Hydrator\StationHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\PublicTransport\StationsService;

class StationsController extends AbstractAuthenticatedApiController {

	/**
	 * @var StationsService
	 */
	protected $stationsService;

	public function __construct( AuthenticationService $authenticationService, StationsService $stationsService ) {
		parent::__construct( $authenticationService );
		$this->stationsService = $stationsService;
	}

	public function get ( array $params ) {
		$this->assertAccessToken();
		$this->authenticationService->findAuthenticatedUser($this->accessToken);

		$station = $this->stationsService->getStation($params['id']);
		return $this->createDefaultViewModel($this->extractObject(StationHydrator::class, $station));
	}
}