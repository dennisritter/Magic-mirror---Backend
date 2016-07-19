<?php

namespace Perna\Service;

use Doctrine\MongoDB\Query\Query;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Perna\Document\City;
use ZfrRest\Http\Exception\Client\NotFoundException;

/**
 * WeatherLocationService
 * Responsible for retrieval of Weather Locations
 *
 * @author      Jannik Portz
 * @package     Perna\Service
 */
class WeatherLocationService {

	protected $documentManager;

	public function __construct ( DocumentManager $documentManager ) {
		$this->documentManager = $documentManager;
	}

	/**
	 * Finds a weather location by its id
	 * @param     int       $id       The id of the location to find
	 * @return    City                The city document
	 *
	 * @throws    NotFoundException   If no object with the specified id exists
	 */
	public function findLocation ( int $id ) : City {
		$result = $this->documentManager->getRepository( City::class )->find( $id );

		if ( !$result instanceof City )
			throw new NotFoundException("Weather Location with id {$id} does not exist.");

		return $result;
	}

	/**
	 * Retrieves the nearest locations to the specified geo coordinates
	 * @param     float     $latitude       The latitude part of the geo coordinate to search for
	 * @param     float     $longitude      The latitude part of the geo coordinate to search for
	 * @param     int       $numberResults  The number of results to retrieve
	 *
	 * @return    City[]                    Array of city objects representing the nearest locations to the specified location
	 */
	public function findNearbyLocations ( float $latitude, float $longitude, int $numberResults = 10 ) : array {
		$qb = $this->documentManager->getRepository( City::class )->createQueryBuilder();
		$qb->field('location')
			->geoNear($latitude, $longitude)
			->spherical(true);
		$qb->limit($numberResults);

		$query = $qb->getQuery();
		return $this->getResultsFromQuery( $query );
	}

	/**
	 * Retrieves a set of Locations that match the specified search query.
	 * Will return an empty array if the trimmed query is shorter than two characters.
	 * @param     string    $query          The search query
	 * @param     int       $numberResults  The number of location results to retrieve
	 * @return    City[]                    The location results
	 */
	public function autocompleteLocations ( string $query, int $numberResults = 10 ) : array {
		$query = trim( $query );
		if ( strlen( $query ) < 3 )
			return [];

		$regex = '/'. preg_replace('/[\s-–\.]+/', '.*', $query) .'/i';
		$qb = $this->documentManager->getRepository( City::class )->createQueryBuilder();
		$qb->field('name')
			->equals( new \MongoRegex( $regex ) );
		$qb->limit( $numberResults );

		$query = $qb->getQuery();
		return $this->getResultsFromQuery( $query );
	}

	/**
	 * Executes the specified Query and fetches the results
	 * @param     Query     $query    The Query to execute
	 * @return    array               The results or an empty array if an error occurred
	 */
	protected function getResultsFromQuery ( Query $query ) : array {
		try {
			$cursor = $query->execute();
			$results = [];
			foreach ( $cursor as $r )
				$results[] = $r;

			return $results;
		} catch ( MongoDBException $e ) {
			error_log( $e->getMessage() );
			return [];
		}
	}
}