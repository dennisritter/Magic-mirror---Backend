<?php

namespace Perna\Controller\PublicTransport;

use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Hydrator\StationHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\PublicTransport\StationsService;
use Perna\Service\PublicTransport\VBBAccessService;
use Swagger\Annotations as SWG;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;

class StationSearchController extends AbstractAuthenticatedApiController {

	/**
	 * @var StationsService
	 */
	protected $stationsService;

	public function __construct( AuthenticationService $authenticationService, StationsService $stationsService ) {
		parent::__construct( $authenticationService );
		$this->stationsService = $stationsService;
	}

	/**
	 * @SWG\Get(
	 *   path="/publicTransport/stations/search",
	 *   summary="VBB Station Search",
	 *   description="Searches for stations at VBB API",
	 *   operationId="publicTransportSearch",
	 *   tags={"publicTransport"},
	 *   @SWG\Parameter(
	 *    in="query",
	 *    name="query",
	 *    type="string",
	 *    required=true,
	 *    description="The search query for the station (required)",
	 *    default="Hauptbahnhof"
	 *   ),
	 *   @SWG\Parameter(ref="#/parameters/accessToken"),
	 *   @SWG\Response(
	 *    response="200",
	 *    description="Stations matching the provided search string.",
	 *    @SWG\Schema(
	 *      @SWG\Property(property="success", type="boolean", default=true),
	 *      @SWG\Property(property="data", type="array", description="The search results, sorted by relevance", @SWG\Items(ref="Station"))
	 *    ),
	 *   ),
	 *   @SWG\Response(response="403", ref="#/responses/403"),
	 *   @SWG\Response(response="422", ref="#/responses/422"),
	 *   @SWG\Response(response="503", ref="#/responses/503")
	 * )
	 */
	public function get () {
		$this->assertAccessToken();
		$this->authenticationService->findAuthenticatedUser( $this->accessToken );

		$query = $this->params()->fromQuery('query', null);

		if ( $query === null )
			throw new UnprocessableEntityException("The query parameter 'query' must be present.");

		$results = $this->stationsService->findStations( $query );
		return $this->createDefaultViewModel( $this->extractObject( StationHydrator::class, $results ) );
	}
}