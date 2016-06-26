<?php
/**
 * Created by PhpStorm.
 * User: Hannes
 * Date: 22.06.2016
 * Time: 15:14
 */

namespace Perna\Service\PublicTransport;


use DoctrineMongoODMModule\Options\DocumentManager;
use Perna\Document\Station;

class DepartureService{

    protected $documentManger;

    protected $accessService;

    function __construct( DocumentManager $documentManager, VBBAccessService $accessService ) {
        $this->documentManger = $documentManager;
        $this->accessService = $accessService;
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

    function getNewData ( Station $station){
        $cachedDepartures = $this->accessService->getDepartures( $station );
        $station->setDepartures( $cachedDepartures );
        $station->setFetchedDepartures( new \DateTime("now"));
        $this->documentManger->flush();
        return $cachedDepartures;
    }

}