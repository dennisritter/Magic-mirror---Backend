<?php

namespace Perna\Controller\Weather;

use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Hydrator\CityHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\Weather\GeoNamesAccessService;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;
use Swagger\Annotations as SWG;

class WeatherLocationSearchController extends AbstractAuthenticatedApiController {

	/**
	 * @var       GeoNamesAccessService
	 */
	protected $geoNamesService;

	public function __construct ( AuthenticationService $authenticationService, GeoNamesAccessService $geoNamesService ) {
		parent::__construct( $authenticationService );
		$this->geoNamesService = $geoNamesService;
	}

	/**
	 * @SWG\Get(
	 *   path="/weather/locations/search",
	 *   summary="Weather Location Search",
	 *   description="Searches for the provided query using GeoNames.org API. It is not recommended to use this endpoint for auto-completion but rather for searching with complete city names.",
	 *   operationId="weatherLocationsSearch",
	 *   tags={"weather"},
	 *   @SWG\Parameter(
	 *    in="query",
	 *    name="query",
	 *    type="string",
	 *    required=true,
	 *    description="The search query for the weather locations (required)",
	 *    default="Berlin"
	 *   ),
	 *   @SWG\Parameter(ref="#/parameters/accessToken"),
	 *   @SWG\Response(
	 *    response="200",
	 *    description="Locations matching the provided search string.",
	 *    @SWG\Schema(
	 *      @SWG\Property(property="success", type="boolean", default=true),
	 *      @SWG\Property(property="data", type="array", description="The search results", @SWG\Items(ref="City", maxItems=20))
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
		$query = $this->params()->fromQuery('query', null);

		if ( $query === null )
			throw new UnprocessableEntityException("The query parameter 'query' must be present.");

		$results = $this->geoNamesService->searchCities( $query );
		return $this->createDefaultViewModel( $this->extractObject( CityHydrator::class, $results ) );
	}

}