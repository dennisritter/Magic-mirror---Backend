<?php

namespace Perna\Controller\Weather;

use Perna\Controller\AbstractAuthenticatedApiController;
use Perna\Hydrator\Weather\WeatherDataCacheHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\Weather\WeatherDataService;
use Swagger\Annotations as SWG;

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

	/**
	 * @SWG\Get(
	 *   path="/weather/{locationId}",
	 *   summary="Weather Data",
	 *   description="Retrieves weather data for the specified location.",
	 *   operationId="getWeatherData",
	 *   tags={"weather"},
	 *   @SWG\Parameter(
	 *    in="path",
	 *    name="locationId",
	 *    type="number",
	 *    format="int32",
	 *    required=true,
	 *    description="The id of the location for which to retrieve weather data.",
	 *    default=123456
	 *  ),
	 *  @SWG\Parameter(ref="#/parameters/accessToken"),
	 *  @SWG\Response(
	 *   response="200",
	 *   description="successful operation",
	 *   @SWG\Schema(
	 *    required={"success", "data"},
	 *    @SWG\Property(property="success", type="boolean", default=true),
	 *    @SWG\Property(property="data", ref="WeatherDataCache")
	 *   )
	 *  ),
	 *  @SWG\Response(response="403", ref="#/responses/403"),
	 *  @SWG\Response(response="422", ref="#/responses/422"),
	 *  @SWG\Response(
	 *   response="404",
	 *   description="Weather location could not be found",
	 *   @SWG\Schema(
	 *    @SWG\Property(property="status_code", type="number", format="int32", default=404, description="The HTTP status code"),
	 *    @SWG\Property(property="message", type="string", description="The error message")
	 *   )
	 *  )
	 * )
	 */
	public function get ( array $params ) {
		$this->assertAccessToken();
		$this->authenticationService->findAuthenticatedUser( $this->accessToken );

		$data = $this->weatherDataService->getWeatherData( (int) $params['id'] );
		return $this->createDefaultViewModel( $this->extractObject( WeatherDataCacheHydrator::class, $data ) );
	}
}