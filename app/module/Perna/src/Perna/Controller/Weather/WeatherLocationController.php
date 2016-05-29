<?php

namespace Perna\Controller\Weather;


use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Hydrator\CityHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\WeatherLocationService;

class WeatherLocationController extends AbstractAuthenticatedApiController {

	/**
	 * @var       WeatherLocationService
	 */
	protected $weatherLocationService;
	
	public function __construct( AuthenticationService $authenticationService, WeatherLocationService $weatherLocationService ) {
		parent::__construct( $authenticationService );
		$this->weatherLocationService = $weatherLocationService;
	}
	
	public function get ( array $params ) {
		$this->assertAccessToken();
		$this->authenticationService->findAuthenticatedUser( $this->accessToken );
		$id = (int) $params['id'];
		
		$location = $this->weatherLocationService->findLocation( $id );
		return $this->createDefaultViewModel( $this->extractObject( CityHydrator::class, $location ) );
	}
}