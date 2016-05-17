<?php

namespace Perna\Hydrator;


use Perna\Document\CalendarModule;

class CalendarModuleHydrator extends AbstractModuleHydrator {
    public function extract($object) : array {
        /** @var CalendarModule $object */
        $data = parent::extract($object);
        $data["calendarIds"] =  $object->getCalendarIds();
        return $data;
    }

    public function hydrate(array $data, $object) {
        /** @var CalendarModule $object */
        parent::hydrate($data, $object);
        $object->setCalendarIds( $data["calendarIDs"] );
        return $object;
    }


}