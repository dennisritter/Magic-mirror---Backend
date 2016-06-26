<?php

namespace Perna\Service\PublicTransport;
use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Document\Station;
use ZfrRest\Http\Exception\Client\NotFoundException;

/**
 * Service responsible for Public Transport Stations
 *
 * @author      Jannik Portz
 * @package     Perna\Service\PublicTransport
 */
class StationsService {

	/**
	 * @var VBBAccessService
	 */
	protected $vbbAccessService;

	/**
	 * @var DocumentManager
	 */
	protected $documentManager;

	public function __construct ( VBBAccessService $VBBAccessService, DocumentManager $documentManager ) {
		$this->vbbAccessService = $VBBAccessService;
		$this->documentManager = $documentManager;
	}

	/**
	 * Gets stations from API and saves them to database if they do not already exist
	 * @param     string    $query    The query to search for
	 * @return    Station[]           Array of Stations matching the search query
	 */
	public function findStations ( string $query ) : array {
		$results = $this->vbbAccessService->findStations( $query );
		$repo = $this->documentManager->getRepository( Station::class );
		$qb = $repo->createQueryBuilder();
		$ids = array_map( function ( Station $s ) {
			return $s->getExtId();
		}, $results );

		// Fetch all records from db at once
		$qb->find()
			->field('_id')->in( $ids );

		$query = $qb->getQuery();
		$dbResults = $query->execute();
		$findDbResult = function ( Station $s ) use ( $dbResults ) {
			foreach ( $dbResults as $r ) {
				/** @var Station $r */
				if ( $r->getExtId() == $s->getExtId() )
					return $r;
			}

			return null;
		};

		foreach ( $results as $result ) {
			if ( $findDbResult( $result ) !== null )
				continue;

			$this->documentManager->persist( $result );
		}

		$this->documentManager->flush();
		return $results;
	}

	/**
	 * Retrieves a single station by its Id (extId)
	 * @param     string    $id       The id / extId of the station
	 * @return    Station             The station matching the id
	 *
	 * @throws    NotFoundException   If the station could not be found
	 */
	public function getStation ( string $id ) : Station {
		$station = $this->documentManager->getRepository( Station::class )->find( $id );
		if ( !$station instanceof Station )
			throw new NotFoundException("A station with id {$id} could not be found.");

		return $station;
	}
}