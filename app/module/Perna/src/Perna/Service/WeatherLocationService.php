<?php

namespace Perna\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Document\City;

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
	 * Retrieves the nearest locations to the specified geo coordinates
	 * @param     float     $latitude       The latitude part of the geo coordinate to search for
	 * @param     float     $longitude      The latitude part of the geo coordinate to search for
	 * @param     int       $numberResults  The number of results to retrieve
	 *
	 * @return    City[]                    Array of city objects representing the nearest locations to the specified location
	 */
	public function findNearbyLocations ( float $latitude, float $longitude, int $numberResults = 10 ) {
		$qb = $this->documentManager->getRepository( City::class )->createQueryBuilder();
		$qb->field('location')
			->geoNear($latitude, $longitude);
		$qb->limit($numberResults);

		$query = $qb->getQuery();
		$results = $query->execute();

		return $results;
	}
}