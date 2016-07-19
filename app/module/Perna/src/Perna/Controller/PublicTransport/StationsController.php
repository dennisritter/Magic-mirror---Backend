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

	/**
	 * @SWG\Get(
	 *   path="/publicTransport/stations/{stationId}",
	 *   summary="Get Single Station",
	 *   description="Retrieves a single station item by its id",
	 *   operationId="getStation",
	 *   tags={"publicTransport"},
	 *   @SWG\Parameter(
	 *    in="path",
	 *    name="stationId",
	 *    type="string",
	 *    required=true,
	 *    description="The id of the station as string with precending zeros",
	 *    default="009835756"
	 *   ),
	 *   @SWG\Response(
	 *    response="200",
	 *    description="Station has successfully been retrieved",
	 *    @SWG\Schema(
	 *      @SWG\Property(property="success", type="boolean", default="true"),
	 *      @SWG\Property(property="data", ref="Station")
	 *    )
	 *   )
	 * )
	 */
	public function get ( array $params ) {
		$this->assertAccessToken();
		$this->authenticationService->findAuthenticatedUser($this->accessToken);

		$station = $this->stationsService->getStation($params['id']);
		return $this->createDefaultViewModel($this->extractObject(StationHydrator::class, $station));
	}
}