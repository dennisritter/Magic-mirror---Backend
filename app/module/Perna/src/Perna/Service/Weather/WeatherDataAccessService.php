<?php

namespace Perna\Service\Weather;

use Perna\Document\City;
use Perna\Document\CurrentWeatherData;
use Perna\Document\DailyWeatherData;
use Perna\Document\TemporalWeatherData;
use Perna\Exception\WeatherDataAccessException;
use Perna\Hydrator\Weather\CurrentWeatherDataHydrator;
use Perna\Hydrator\Weather\DailyWeatherDataHydrator;
use Perna\Hydrator\Weather\TemporalWeatherDataHydrator;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Stdlib\ParametersInterface;

/**
 * Service responsible for Weather Data Access
 *
 * @author      Jannik Portz
 * @package     Perna\Service\Weather
 */
class WeatherDataAccessService {

	const ENDPOINT_CURRENT_WEATHER_DATA = 'http://api.openweathermap.org/data/2.5/weather';
	const ENDPOINT_FORECAST = 'http://api.openweathermap.org/data/2.5/forecast';
	const ENDPOINT_FORECAST_DAILY = 'http://api.openweathermap.org/data/2.5/forecast/daily';

	// TODO: move to config
	const API_KEY = 'f0bc795ee8f69d0e28cfbaaa5a965475';

	/**
	 * @var       CurrentWeatherDataHydrator
	 */
	protected $currentHydrator;

	/**
	 * @var       TemporalWeatherDataHydrator
	 */
	protected $temporalHydrator;

	/**
	 * @var       DailyWeatherDataHydrator
	 */
	protected $dailyHydrator;

	/**
	 * @var       Client
	 */
	protected $httpClient;

	public function __construct ( CurrentWeatherDataHydrator $currentHydrator, TemporalWeatherDataHydrator $temporalHydrator, DailyWeatherDataHydrator $dailyHydrator, Client $httpClient ) {
		$this->currentHydrator = $currentHydrator;
		$this->temporalHydrator = $temporalHydrator;
		$this->dailyHydrator = $dailyHydrator;
		$this->httpClient = $httpClient;
	}

	/**
	 * Gets the current weather data from the API
	 * @param     City      $location The weather location for which to retrieve the results
	 * @return    CurrentWeatherData  The CurrentWeatherData object containing the weather data
	 */
	public function getCurrentWeatherData ( City $location ) : CurrentWeatherData {
		$data = $this->fetchData( $location, self::ENDPOINT_CURRENT_WEATHER_DATA );
		return $this->currentHydrator->hydrate( $data, new CurrentWeatherData() );
	}

	/**
	 * Gets the 5days / 3hours forecasts from the API
	 * @param     City              $location The weather location for which to retrieve the results
	 * @return    TemporalWeatherData[] The forecast items
	 *
	 * @throws    WeatherDataAccessException
	 */
	public function getTemporalWeatherData ( City $location ) : array {
		$data = $this->fetchData( $location, self::ENDPOINT_FORECAST );
		if ( !array_key_exists( 'list', $data ) )
			throw new WeatherDataAccessException("Key 'list' is not present on the response.");

		$forecasts = [];
		foreach ( $data['list'] as $item )
			$forecasts[] = $this->temporalHydrator->hydrate( $item, new TemporalWeatherData() );

		return $forecasts;
	}

	/**
	 * Gets the 16days / daily forecasts from the API
	 * @param     City              $location The weather location for which to retrieve the results
	 * @return    DailyWeatherData[]    The forecast items
	 *
	 * @throws    WeatherDataAccessException
	 */
	public function getDailyWeatherData ( City $location ) : array {
		$data = $this->fetchData( $location, self::ENDPOINT_FORECAST_DAILY );
		if ( !array_key_exists( 'list', $data ) )
			throw new WeatherDataAccessException("Key 'list' is not present on the response.");

		$forecasts = [];
		foreach ( $data['list'] as $item )
			$forecasts[] = $this->dailyHydrator->hydrate( $item, new DailyWeatherData() );

		return $forecasts;
	}

	/**
	 * Fetches weather data from the specified endpoint for the specified location
	 * @param     City      $location The weather location for which to retrieve the results
	 * @param     string    $endpoint The endpoint to call
	 *
	 * @return    array               Array containing response data
	 * @throws    WeatherDataAccessException  If request could not be sent
	 *                                        If JSON content could not be parsed
	 */
	protected function fetchData ( City $location, string $endpoint ) : array {
		$request = $this->createRequest( $location, $endpoint );
		$response = $this->httpClient->send( $request );
		
		if ( !$response->isSuccess() || !$response->isOk() )
			throw new WeatherDataAccessException("Could not fetch data at endpoint {$endpoint} for location {$location->getId()}");

		$data = json_decode( $response->getBody(), true );

		if ( $data === false || !is_array( $data ) )
			throw new WeatherDataAccessException("Could not parse JSON in weather data response from endpoint {$endpoint} for location {$location->getLocation()}");

		return $data;
	}

	/**
	 * Creates a basic request to OpenWeatherMap for a specific endpoint
	 * @param     City              $location The weather location for which to retrieve the results
	 * @param     string    $endpoint The request endpoint
	 *
	 * @return    Request             The creates Request object
	 */
	protected function createRequest ( City $location, string $endpoint ) : Request {
		$request = new Request();
		$request->setUri($endpoint);
		$request->setMethod(Request::METHOD_GET);

		/** @var ParametersInterface $query */
		$query = $request->getQuery();
		$query->set('appid', self::API_KEY);

		$geo = $location->getLocation();
		$query->set('lat', $geo[0]);
		$query->set('lon', $geo[1]);

		return $request;
	}
}