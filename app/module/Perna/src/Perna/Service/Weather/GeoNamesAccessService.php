<?php

namespace Perna\Service\Weather;

use Perna\Document\City;
use Perna\Hydrator\CityHydrator;
use Zend\Http\Client;
use Zend\Http\Request;
use ZfrRest\Http\Exception\Server\ServiceUnavailableException;

/**
 * Service responsible of fetching data from GeoNames API
 *
 * @author      Jannik Portz
 * @package     Perna\Service\Weather
 */
class GeoNamesAccessService {

	// TODO: move to config
	const USERNAME = 'perna';
	const API_HOST = 'http://api.geonames.org/';

	protected $cityHydrator;

	public function __construct ( CityHydrator $cityHydrator ) {
		$this->cityHydrator = $cityHydrator;
	}

	/**
	 * Performs a Search on the GeoNames API
	 * @param     string    $query    The search query to use
	 * @return    City[]              Results as City-objects
	 */
	public function searchCities ( string $query ) : array {
		$request = $this->createBasicRequest();
		$request->setUri( self::API_HOST . 'searchJSON' );
		$request->getQuery()->set('q', $query);

		$data = $this->getResultData( $request );
		
		if ( !array_key_exists('geonames', $data) )
			return [];
		
		return $this->getCitiesFromData( $data['geonames'] );
	}

	/**
	 * Creates a basic request with commonly used fields for GeoNames API
	 * @return    Request
	 */
	protected function createBasicRequest () : Request {
		$r = new Request();
		$r->setMethod( Request::METHOD_GET );
		$query = $r->getQuery();
		$query->set('username', self::USERNAME);
		$query->set('maxRows', 20);
		$query->set('style', 'short');
		$query->set('lang', 'en');
		return $r;
	}

	/**
	 * Sends the Request and returns the results as associative array
	 * @param     Request   $request  The request to send
	 * @return    array               Associative array containing the response data
	 * @throws    ServiceUnavailableException If the request could not be sent or the response content could not be parsed
	 */
	protected function getResultData ( Request $request ) : array {
		$client = new Client();

		try {
			$response = $client->send( $request );
		} catch ( \Exception $e ) {
			error_log( $e->getTraceAsString() );
			throw new ServiceUnavailableException();
		}

		$data = json_decode( trim( $response->getBody() ), true );

		if ( $data === false || !is_array( $data ) )
			throw new ServiceUnavailableException();

		return $data;
	}

	/**
	 * Converts an array of geoname data sets to City-objects
	 * @param     array     $data     Sequential array containing data sets as associative arrays
	 * @return    City[]              List of hydrated Cities
	 */
	protected function getCitiesFromData ( array $data ) : array {
		$cities = [];
		foreach ( $data as $gn )
			$cities[] = $this->cityHydrator->hydrateFromGeoNameResult( $gn, new City() );
		return $cities;
	}

}