<?php
/**
 * Created by PhpStorm.
 * User: Hannes
 * Date: 22.06.2016
 * Time: 15:14
 */

namespace Perna\Service\PublicTransport;


use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\PersistentCollection;
use Perna\Document\Station;
use ZfrRest\Http\Exception\Client\NotFoundException;

class DepartureService {


    /**
     * @var DocumentManager
     */
    protected $documentManager;

    /**
     * @var VBBAccessService
     */
    protected $accessService;

    /**
     * @var StationsService
     */
    protected $stationsService;

    function __construct( DocumentManager $documentManager, VBBAccessService $accessService, StationsService $stationsService ) {
        $this->documentManager = $documentManager;
        $this->accessService = $accessService;
        $this->stationsService = $stationsService;
    }

    function getDepartures ( Station $station, array $products ) : array {
        $cachedDepartures = $station->getDepartures()->toArray();

        if ( count( $cachedDepartures ) < 1 ){
            $deps = $this->getNewData( $station );
        } elseif ( $station->getFetchedDepartures() >= (new \DateTime('now'))->sub(new \DateInterval('PT2M')) ) {
            $deps = $cachedDepartures;
        } else {
            $deps = $this->getNewData( $station );
        }

        if ( count( $products ) < 1 ) {
            return $deps;
        }

        $data = [];
        foreach ( $deps as $departure ) {
            if ( in_array( $departure->getProduct(), $products ) )
                $data[] = $departure;
        }

        return $data;
    }

    function getNewData ( Station $station) : array {
        $cachedDepartures = $this->accessService->getDepartures( $station );
        $station->setDepartures( $cachedDepartures );
        $station->setFetchedDepartures( new \DateTime("now"));
        $this->documentManager->flush();
        return $cachedDepartures;
    }

    function getDepartureData ( string $stationId, array $products ) : array {
        $station = $this->stationsService->getStation( $stationId );
        if ( $station == null )
            throw new NotFoundException("Station with id {$stationId} could not be found.");

        return $this->getDepartures( $station, $products );
    }
}