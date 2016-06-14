<?php

namespace Perna\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Swagger\Annotations as SWG;

/**
 * Document-Class for Weather
 *
 * @ODM\EmbeddedDocument
 *
 * @author      Johannes Knauft
 * @package     Perna\Documents
 */
class WeatherModule extends Module {

    public function __construct() {
        parent::__construct();
        $this->type = "weather";
    }

    /**
     * @ODM\Field(
     *     name = "calendarIds",
     *     type = "int"
     * )
     * @var int array of calendar ids
     */
    protected $locationId;

    /**
     * @return int
     */
    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * @param int $locationId
     */
    public function setLocationId($locationId)
    {
        $this->locationId = $locationId;
    }
    
    

    function __toString()
    {
        return $this->id." ".$this->height." ". $this->width." ".$this->xPosition." ".$this->yPosition." ";
    }


}