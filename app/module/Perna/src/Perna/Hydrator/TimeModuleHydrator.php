<?php

namespace Perna\Hydrator;


use Perna\Document\TimeModule;

class TimeModuleHydrator extends AbstractModuleHydrator {
    public function extract($object) : array {
        /** @var TimeModule $object */
        $data = parent::extract($object);
        return $data;
    }

    public function hydrate(array $data, $object) {
        /** @var TimeModule $object */
        parent::hydrate($data, $object);
        return $object;
    }
}