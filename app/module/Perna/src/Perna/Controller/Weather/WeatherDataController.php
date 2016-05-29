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
	 *  @SWG\Response(
	 *   response="default",
	 *   description="unprocessable entity.",
	 *   @SWG\Schema(ref="ResponseError")
	 *  ),
	 *   @SWG\Response(response="500", ref="#/responses/500")
	 * )
	 */
	public function get ( array $params ) {
		$this->assertAccessToken();
		$this->authenticationService->findAuthenticatedUser( $this->accessToken );

		$data = $this->weatherDataService->getWeatherData( (int) $params['id'] );
		return $this->createDefaultViewModel( $this->extractObject( WeatherDataCacheHydrator::class, $data ) );
	}
}