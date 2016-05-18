<?php

namespace Perna\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Document-Class for CalendarModules
 *
 * @ODM\EmbeddedDocument
 *
 * @author      Johannes Knauft
 * @package     Perna\Documents
 */
class CalendarModule extends Module {

    public function __construct() {
        parent::__construct();
        $this->type = "calendar";
    }

    /**
     * @ODM\Field(
     *     name = "calendarIds",
     *     type = "collection"
     * )
     * @var string[] array of calendar ids
     */
    protected $calendarIds;

    /**
     * @return string[]
     */
    public function getCalendarIds() {
        return $this->calendarIds;
    }

    /**
     * @param string[] $calendarIds
     */
    public function setCalendarIds( $calendarIds ) {
        $this->calendarIds = $calendarIds;
    }

    function __toString()
    {
        return $this->id." ".$this->height." ". $this->width." ".$this->xPosition." ".$this->yPosition." ";
    }


}