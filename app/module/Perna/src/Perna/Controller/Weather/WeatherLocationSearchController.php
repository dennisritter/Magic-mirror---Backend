<?php

namespace Perna\Controller\Weather;

use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Hydrator\CityHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\Weather\GeoNamesAccessService;
use ZfrRest\Http\Exception\Client\UnprocessableEntityException;

class WeatherLocationSearchController extends AbstractAuthenticatedApiController {

	/**
	 * @var       GeoNamesAccessService
	 */
	protected $geoNamesService;

	public function __construct ( AuthenticationService $authenticationService, GeoNamesAccessService $geoNamesService ) {
		parent::__construct( $authenticationService );
		$this->geoNamesService = $geoNamesService;
	}

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