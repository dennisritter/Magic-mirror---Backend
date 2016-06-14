<?php

namespace Perna\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Swagger\Annotations as SWG;

/**
 * Document-Class for TimeModule
 *
 * @ODM\EmbeddedDocument
 *
 * @author      Johannes Knauft
 * @package     Perna\Documents
 */
class TimeModule extends Module {

    public function __construct() {
        parent::__construct();
        $this->type = "time";
    }
    

    function __toString()
    {
        return $this->id." ".$this->height." ". $this->width." ".$this->xPosition." ".$this->yPosition." ";
    }
}