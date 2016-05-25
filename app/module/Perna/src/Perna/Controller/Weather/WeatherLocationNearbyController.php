<?php

namespace Perna\Controller\Weather;

use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Hydrator\CityHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\WeatherLocationService;

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
		//$this->assertAccessToken();
		$params = $this->params();
		$lat = floatval( $params->fromQuery('latitude') );
		$lng = floatval( $params->fromQuery('longitude') );
		$numResults = floatval( min( $params->fromQuery('numberResults', 10), 100 ) );

		$results = $this->locationService->findNearbyLocations( $lat, $lng, $numResults );
		return $this->createDefaultViewModel( $this->extractObject( CityHydrator::class, $results ) );
	}
}