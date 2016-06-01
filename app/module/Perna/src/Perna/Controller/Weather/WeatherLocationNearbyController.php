<?php

namespace Perna\Controller\Weather;

use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Hydrator\CityHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\WeatherLocationService;
use Swagger\Annotations as SWG;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;

class WeatherLocationNearbyController extends AbstractAuthenticatedApiController {

	/**
	 * @var       WeatherLocationService
	 */
	protected $locationService;

	public function __construct( AuthenticationService $authenticationService, WeatherLocationService $weatherLocationService ) {
		parent::__construct( $authenticationService );
		$this->locationService = $weatherLocationService;
	}

	/**
	 * @SWG\Get(
	 *   path="/weather/locations/nearby",
	 *   summary="Weather Location Nearby Search",
	 *   description="Determines weather locations that are near the specified geo coordinates. Results are automatically sorted by closeness.",
	 *   operationId="weatherLocationsNearby",
	 *   tags={"weather"},
	 *   @SWG\Parameter(
	 *    in="query",
	 *    name="latitude",
	 *    type="number",
	 *    required=true,
	 *    description="The latitude of the search location as float value (required)",
	 *    default="52.5451160"
	 *   ),
	 *   @SWG\Parameter(
	 *    in="query",
	 *    name="longitude",
	 *    type="number",
	 *    required=true,
	 *    description="The longitude of the search location as float value (required)",
	 *    default="13.3552320"
	 *   ),
	 *   @SWG\Parameter(ref="#/parameters/accessToken"),
	 *   @SWG\Response(
	 *    response="200",
	 *    description="The closest locations have successfully been retrieved",
	 *    @SWG\Schema(
	 *      @SWG\Property(property="success", type="boolean", default=true),
	 *      @SWG\Property(property="data", type="array", description="The nearby locations", @SWG\Items(ref="City"))
	 *    ),
	 *   ),
	 *   @SWG\Response(response="403", ref="#/responses/403"),
	 *   @SWG\Response(response="422", ref="#/responses/422")
	 * )
	 */
	public function get () {
		$this->assertAccessToken();
		// Check if any user is actually authenticated
		$this->authenticationService->findAuthenticatedUser( $this->accessToken );

		$params = $this->params();

		$lat = floatval( $params->fromQuery('latitude', null) );
		$lng = floatval( $params->fromQuery('longitude', null) );

		if ( $lat === null || $lng === null )
			throw new UnprocessableEntityException("The query parameters 'latitude' and 'longitude' must be present and valid.");

		$results = $this->locationService->findNearbyLocations( $lat, $lng, 20 );
		return $this->createDefaultViewModel( $this->extractObject( CityHydrator::class, $results ) );
	}
}