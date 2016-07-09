<?php

namespace Perna\Controller\Weather;

use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Hydrator\CityHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\Weather\GeoNamesAccessService;
use Perna\Service\WeatherLocationService;
use Swagger\Annotations as SWG;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;

class WeatherLocationNearbyController extends AbstractAuthenticatedApiController {

	/**
	 * @var       WeatherLocationService
	 */
	protected $locationService;

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
	 *   path="/weather/locations/nearby",
	 *   summary="Weather Location Nearby Search",
	 *   description="Determines the nearest city to the specified geo coordinates",
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
	 *    description="The closest location has successfully been retrieved",
	 *    @SWG\Schema(
	 *      @SWG\Property(property="success", type="boolean", default=true),
	 *      @SWG\Property(property="data", ref="City", description="The nearest location")
	 *    ),
	 *   ),
	 *   @SWG\Response(response="403", ref="#/responses/403"),
	 *   @SWG\Response(response="422", ref="#/responses/422"),
	 *   @SWG\Response(response="404", ref="#/responses/404")
	 * )
	 */
	public function get () {
		$this->assertAccessToken();
		// Check if any user is actually authenticated
		$this->authenticationService->findAuthenticatedUser( $this->accessToken );

		$params = $this->params();

		$lat = $params->fromQuery('latitude', null);
		$lng = $params->fromQuery('longitude', null);

		if ( $lat === null || $lng === null )
			throw new UnprocessableEntityException("The query parameters 'latitude' and 'longitude' must be present and valid.");

		$result = $this->geoNamesService->findNearestCity( floatval($lat), floatval($lng) );
		return $this->createDefaultViewModel( $this->extractObject( CityHydrator::class, $result ) );
	}
}