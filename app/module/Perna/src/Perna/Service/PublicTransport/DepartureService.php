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

class DepartureService{


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
        $this->documentManger = $documentManager;
        $this->accessService = $accessService;
        $this->stationsService = $stationsService;
    }

    function getDepartures ( Station $station ) : array {
        $cachedDepartures = $station->getDepartures();

        if( count( $cachedDepartures ) < 1 ){
            return $this->getNewData( $station );

        } else {
            if( $station->getFetchedDepartures() >= (new \DateTime('now'))->sub(new \DateInterval('PT2M'))){
                return $cachedDepartures;
            }

            return $this->getNewData( $station );
        }
        
    }

    function getNewData ( Station $station) : array {
        $cachedDepartures = $this->accessService->getDepartures( $station );
        $station->setDepartures( $cachedDepartures );
        $station->setFetchedDepartures( new \DateTime("now"));
        $this->documentManager->flush();
        return $cachedDepartures;
    }

    function getDepartureData ( string $stationId) : array {
        $station = $this->stationsService->getStation( $stationId );
        return $this->getNewData( $station );
    }

}