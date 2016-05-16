<?php

namespace Perna\Hydrator;


use Perna\Document\CalenderModule;

class CalenderModuleHydrator extends AbstractModuleHydrator {
    public function extract($object) : array {
        /** @var CalenderModule $object */
        $data = parent::extract($object);
        $data["calenderIds"] =  $object->getCalenderIds();
    }

    public function hydrate(array $data, $object) {
        /** @var CalenderModule $object */
        parent::hydrate($data, $object);
        $object->setCalenderIds( $data["calenderIDs"] );
        return $object;
    }


}