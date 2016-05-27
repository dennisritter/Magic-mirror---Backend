<?php

namespace Perna\Controller\Weather;

use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Hydrator\CityHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\WeatherLocationService;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;

class WeatherLocationAutocompleteController extends AbstractAuthenticatedApiController {

	/**
	 * @var       WeatherLocationService
	 */
	protected $locationService;

	public function __construct( AuthenticationService $authenticationService, WeatherLocationService $locationService ) {
		parent::__construct( $authenticationService );
		$this->locationService = $locationService;
	}

	/**
	 * @SWG\Get(
	 *   path="/weather/locations/autocomplete",
	 *   summary="Weather Location Autocomplete",
	 *   description="Autocompletes the provided search query and returns matching weather locations. The search is case-insensitive and tries to be agnostic concerning separators like hyphens or whitespace.",
	 *   operationId="weatherLocationsAutocomplete",
	 *   tags={"weather"},
	 *   @SWG\Parameter(
	 *    in="query",
	 *    name="query",
	 *    type="string",
	 *    required=true,
	 *    description="The search query for the weather locations (required)",
	 *    default="Berlin-Pankow"
	 *   ),
	 *   @SWG\Parameter(
	 *    in="header",
	 *    name="Access-Token",
	 *    type="string",
	 *    required=true,
	 *    description="A valid access token"
	 *   ),
	 *   @SWG\Response(
	 *    response="200",
	 *    description="Locations matching the provided search string. Not more than 10 items.",
	 *    @SWG\Schema(
	 *      type="array",
	 *      @SWG\Items(ref="City")
	 *    ),
	 *   ),
	 *   @SWG\Response(
	 *    response="422",
	 *    description="The request is invalid."
	 *   )
	 * )
	 */
	public function get () {
		$this->assertAccessToken();
		// Check if any user is actually authenticated
		$this->authenticationService->findAuthenticatedUser( $this->accessToken );
		$query = $this->params()->fromQuery('query', null);

		if ( $query === null )
			throw new UnprocessableEntityException("The query parameter 'query' must be present.");

		$results = $this->locationService->autocompleteLocations( $query );
		return $this->createDefaultViewModel( $this->extractObject( CityHydrator::class, $results ) );
	}
}