<?php

namespace Perna\Controller\Weather;

use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Hydrator\CityHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\WeatherLocationService;
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

	public function get () {
		$this->assertAccessToken();
		$params = $this->params();

		$lat = floatval( $params->fromQuery('latitude', null) );
		$lng = floatval( $params->fromQuery('longitude', null) );
		$numResults = floatval( min( $params->fromQuery('numberResults', 10), 100 ) );

		if ( $lat === null || $lng === null )
			throw new UnprocessableEntityException("The query parameters 'latitude' and 'longitude' must be present and valid.");

		$results = $this->locationService->findNearbyLocations( $lat, $lng, $numResults );
		return $this->createDefaultViewModel( $this->extractObject( CityHydrator::class, $results ) );
	}
}