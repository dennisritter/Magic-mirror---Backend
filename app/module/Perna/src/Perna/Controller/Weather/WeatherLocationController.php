<?php

namespace Perna\Controller\Weather;


use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Hydrator\CityHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\Weather\GeoNamesAccessService;
use Perna\Service\WeatherLocationService;
use Swagger\Annotations as SWG;

class WeatherLocationController extends AbstractAuthenticatedApiController {

	/**
	 * @var       GeoNamesAccessService
	 */
	protected $geoNamesService;
	
	public function __construct( AuthenticationService $authenticationService, GeoNamesAccessService $geoNamesService ) {
		parent::__construct( $authenticationService );
		$this->geoNamesService = $geoNamesService;
	}

	/**
	 * @SWG\Get(
	 *   path="/weather/locations/{id}",
	 *   summary="Get Weather Location",
	 *   description="Retrieves a single weather location item by its id",
	 *   operationId="getWeatherLocation",
	 *   tags={"weather"},
	 *   @SWG\Parameter(
	 *    in="path",
	 *    name="id",
	 *    type="number",
	 *    format="int32",
	 *    required=true,
	 *    description="The id of the weather location to return.",
	 *    default=123456
	 *   ),
	 *   @SWG\Response(
	 *    response="200",
	 *    description="Weather location has successfully been retrieved",
	 *    @SWG\Schema(
	 *      @SWG\Property(property="success", type="boolean", default="true"),
	 *      @SWG\Property(property="data", ref="City")
	 *    )
	 *   )
	 * )
	 */
	public function get ( array $params ) {
		$this->assertAccessToken();
		$this->authenticationService->findAuthenticatedUser( $this->accessToken );
		$id = (int) $params['id'];
		
		$location = $this->geoNamesService->getCityById( $id );
		return $this->createDefaultViewModel( $this->extractObject( CityHydrator::class, $location ) );
	}
}