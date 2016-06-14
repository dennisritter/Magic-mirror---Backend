<?php

namespace Perna\Hydrator;


use Perna\Document\WeatherModule;

class WeatherModuleHydrator extends AbstractModuleHydrator {
    public function extract($object) : array {
        /** @var WeatherModule $object */
        $data = parent::extract($object);
        $data['locationId'] = $object->getLocationId();
        return $data;
    }

    public function hydrate(array $data, $object) {
        /** @var WeatherModule $object */
        $object->setLocationId( $data['locationId'] );
        parent::hydrate($data, $object);
        return $object;
    }
}