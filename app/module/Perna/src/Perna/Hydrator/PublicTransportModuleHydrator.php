<?php

namespace Perna\Hydrator;

use Perna\Document\PublicTransportModule;

class PublicTransportModuleHydrator extends AbstractModuleHydrator {
    public function extract($object) : array {
        /** @var PublicTransportModule $object */
        $data = parent::extract($object);
        $data['stationId'] = $object->getStationId();
        $data['stationName'] = $object->getStationName();
        $data['products'] = $object->getProducts();
        return $data;
    }

    public function hydrate(array $data, $object) {
        /** @var PublicTransportModule $object */
        $object->setStationId( $data['stationId'] );
        $object->setStationName( $data['stationName'] );
        $object->setProducts( $data['products'] );
        parent::hydrate($data, $object);
        return $object;
    }
}

