<?php

namespace Perna\Service\PublicTransport;

use Perna\Document\Station;
use Perna\Hydrator\StationHydrator;
use Zend\Http\Client;
use Zend\Http\Request;
use ZfrRest\Http\Exception\Server\ServiceUnavailableException;

/**
 * Service responsible for access to VBB API
 *
 * @author      Jannik Portz
 * @package     Perna\Service\PublicTransport
 */
class VBBAccessService {

	// TODO: move to config
	const API_KEY = 'knauft-46fb-90dd-784d999485a3';
	const BASE = 'http://demo.hafas.de/openapi/vbb-proxy/';

	/**
	 * @var StationHydrator
	 */
	protected $stationHydrator;

	public function __construct ( StationHydrator $stationHydrator ) {
		$this->stationHydrator = $stationHydrator;
	}

	/**
	 * Searches for Stations at VBB API
	 * @param     string    $searchQuery  The search query. Must be at least two characters long to trigger a request
	 * @return    Station[]               Array of stations that match the search query sorted by weight DESC
	 *
	 * @throws    ServiceUnavailableException If an error occurred while fetching the data
	 */
	public function findStations ( string $searchQuery ) : array {
		if ( strlen( $searchQuery ) < 2 )
			return [];

		$request = $this->createBasicRequest('location.name');
		$query = $request->getQuery();
		$query->set('input', trim( $searchQuery ));
		$query->set('type', 'S');

		$client = new Client();
		$response = $client->send( $request );

		if ( !$response->isSuccess() )
			throw new ServiceUnavailableException();

		$body = $response->getBody();
		$data = json_decode( $body, true );

		if ( !array_key_exists('StopLocation', $data) || !is_array( $stationData = $data['StopLocation'] ) )
			throw new ServiceUnavailableException();

		// Sort descending by station weight
		usort( $stationData, function ( array $sd1, array $sd2 ) {
			return $sd2['weight'] <=> $sd1['weight'];
		} );

		$stations = [];
		foreach ( $stationData as $sd ) {
			$stations[] = $this->stationHydrator->hydrate( $sd, new Station() );
		}

		return $stations;
	}

	/**
	 * Creates a basic HTTP request for a VBB endpoint
	 * @param     string    $endpoint The endpoint name without the base
	 * @return    Request             The request object populated with the full URL, the format and the access id
	 */
	protected function createBasicRequest ( string $endpoint ) : Request {
		$request = new Request();
		$request->setUri( self::BASE . $endpoint );
		$request->setMethod( Request::METHOD_GET );
		$query = $request->getQuery();
		$query->set('accessId', self::API_KEY);
		$query->set('format', 'json');
		return $request;
	}
}