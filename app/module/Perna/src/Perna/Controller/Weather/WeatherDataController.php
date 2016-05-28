<?php

namespace Perna\Controller\Weather;

use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Hydrator\Weather\WeatherDataCacheHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\Weather\WeatherDataService;

/**
 * Class WeatherDataController
 *
 * @author      Jannik Portz
 * @package     Perna\Controller\Weather
 */
class WeatherDataController extends AbstractAuthenticatedApiController {

	protected $weatherDataService;

	public function __construct( AuthenticationService $authenticationService, WeatherDataService $weatherDataService ) {
		parent::__construct( $authenticationService );
		$this->weatherDataService = $weatherDataService;
	}

	public function get ( array $params ) {
		$this->assertAccessToken();
		$this->authenticationService->findAuthenticatedUser( $this->accessToken );

		$data = $this->weatherDataService->getWeatherData( (int) $params['id'] );
		return $this->createDefaultViewModel( $this->extractObject( WeatherDataCacheHydrator::class, $data ) );
	}
}