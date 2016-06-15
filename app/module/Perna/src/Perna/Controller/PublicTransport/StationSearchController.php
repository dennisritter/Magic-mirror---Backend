<?php

namespace Perna\Controller\PublicTransport;

use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Hydrator\StationHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\PublicTransport\VBBAccessService;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;

class StationSearchController extends AbstractAuthenticatedApiController {

	/**
	 * @var VBBAccessService
	 */
	protected $vbbAccessService;

	public function __construct( AuthenticationService $authenticationService, VBBAccessService $vbbAccessService ) {
		parent::__construct( $authenticationService );
		$this->vbbAccessService = $vbbAccessService;
	}

	public function get () {
		$query = $this->params()->fromQuery('query', null);

		if ( $query === null )
			throw new UnprocessableEntityException("The query parameter 'query' must be present.");

		$results = $this->vbbAccessService->findStations( $query );
		return $this->createDefaultViewModel( $this->extractObject( StationHydrator::class, $results ) );
	}
}