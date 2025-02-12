<?php

namespace Perna\Service\Weather;

use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Document\City;
use Perna\Hydrator\CityHydrator;
use Zend\Http\Client;
use Zend\Http\Request;
use ZfrRest\Http\Exception\Client\NotFoundException;
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
	const ENDPOINT_GET = self::API_HOST . 'getJSON';
	const ENDPOINT_SEARCH = self::API_HOST . 'searchJSON';
	const ENDPOINT_NEARBY = self::API_HOST . 'findNearbyPlaceNameJSON';

	protected $cityHydrator;

	protected $documentManager;

	protected $httpClient;

	public function __construct ( CityHydrator $cityHydrator, DocumentManager $documentManager, Client $httpClient ) {
		$this->cityHydrator = $cityHydrator;
		$this->documentManager = $documentManager;
		$this->httpClient = $httpClient;
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
	 * Finds the nearest city according to the specified coordinates
	 * @param     float     $latitude   The Latitude
	 * @param     float     $longitude  The Longitude
	 *
	 * @return    City                  The nearest city to the specified coordinates
	 * @throws    NotFoundException     If no city has been found
	 */
	public function findNearestCity ( float $latitude, float $longitude ) : City {
		$request = $this->createBasicRequest();
		$request->setUri( self::API_HOST . 'findNearbyPlaceNameJSON' );
		$q = $request->getQuery();
		$q->set('lat', $latitude);
		$q->set('lng', $longitude);

		$data = $this->getResultData( $request );
		if ( !array_key_exists('geonames', $data) || count( $data['geonames'] ) < 1 )
			throw new NotFoundException("No close city could be found for the specified coordinates.");

		$gn = $data['geonames'][0];
		return $this->cityHydrator->hydrateFromGeoNameResult( $gn, new City() );
	}

	/**
	 * Tries to find a city by name. Checks if a city exists in the database and if not tries to retrieve it from GeoName API and then writes it in the database
	 * @param     int       $id       The id of the location
	 *
	 * @return    City                The result
	 * @throws    NotFoundException   If a city with the specified id does not exist
	 */
	public function getCityById ( int $id ) : City {
		$city = $this->documentManager->getRepository( City::class )->find( $id );
		if ( $city instanceof City )
			return $city;

		$request = $this->createBasicRequest();
		$request->setUri( self::ENDPOINT_GET );
		$request->getQuery()->set('geonameId', $id);

		$data = $this->getResultData( $request );
		if ( !array_key_exists('geonameId', $data) || $data['geonameId'] != $id )
			throw new NotFoundException("A geo location with id {$id} does not exist.");

		$city = $this->cityHydrator->hydrateFromGeoNameResult( $data, new City() );
		$this->documentManager->persist( $city );
		$this->documentManager->flush();

		return $city;
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
		try {
			$response = $this->httpClient->send( $request );
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