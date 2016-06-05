<?php

namespace Perna\Hydrator;


use Perna\Document\CalendarModule;

class WeatherModuleHydrator extends AbstractModuleHydrator {
    public function extract($object) : array {
        /** @var CalendarModule $object */
        $data = parent::extract($object);
        return $data;
    }

    public function hydrate(array $data, $object) {
        /** @var CalendarModule $object */
        parent::hydrate($data, $object);
        return $object;
    }


}