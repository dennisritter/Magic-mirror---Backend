<?php

namespace Perna\Controller\PublicTransport;

use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Hydrator\DepartureHydrator;
use Perna\Hydrator\StationHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\PublicTransport\DepartureService;
use Swagger\Annotations as SWG;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;

class DepartureController extends AbstractAuthenticatedApiController {

	/**
	 * @var DepartureService
	 */
	protected $departureService;

	public function __construct( AuthenticationService $authenticationService, DepartureService $departureService ) {
		parent::__construct( $authenticationService );
		$this->departureService = $departureService;
	}

	/**
	 * @SWG\Get(
	 *   path="/publicTransport/departures",
	 *   summary="VBB Departures",
	 *   description="Gets Departures for station at VBB API",
	 *   operationId="publicTransportDepartures",
	 *   tags={"publicTransport"},
	 *   @SWG\Parameter(
	 *    in = "query",
	 *    name="id",
	 *    type="string",
	 *    required=true,
	 *    description="The station id for the departures (required)",
	 *    default="9100001"
	 *   ),
	 *   @SWG\Parameter(ref="#/parameters/accessToken"),
	 *   @SWG\Response(
	 *    response="200",
	 *    description="Departures at the given station id",
	 *    @SWG\Schema(
	 *      @SWG\Property(property="success", type="boolean", default=true),
	 *      @SWG\Property(property="data", type="array", description="The departures of the given station id", @SWG\Items(ref="Departure"))
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

		$id = "009003201";

		if ( $id === null )
			throw new UnprocessableEntityException("The query parameter 'id' must be present.");

		$results = $this->departureService->getDepartureData( $id );
		return $this->createDefaultViewModel( $this->extractObject( DepartureHydrator::class, $results ) );
	}
}