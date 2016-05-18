<?php

namespace Perna\Hydrator;


use Perna\Document\Module;

class AbstractModuleHydrator extends AbstractHydrator {
    /** @inheritdoc */
    public function extract ( $object ) : array {
        /** @var Module $object */
        return [
            'id' => $object->getId(),
            'width' => $object->getWidth(),
            'height' => $object->getHeight(),
            'xPosition' => $object->getXPosition(),
            'yPosition' => $object->getYPosition(),
            'type' => $object->getType()
        ];
    }

    /** @inheritdoc */
    public function hydrate ( array $data, $object ) {
        /** @var Module $object */
        $object->setWidth( $data['width'] );
        $object->setHeight( $data['height'] );
        $object->setXPosition( $data['xPosition'] );
        $object->setYPosition( $data['yPosition'] );

        return $object;
    }
}