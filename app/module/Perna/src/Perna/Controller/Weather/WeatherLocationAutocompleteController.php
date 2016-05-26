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