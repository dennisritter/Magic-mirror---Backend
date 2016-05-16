<?php

namespace Perna\Document;

/**
 * Document-Class for CalenderModules
 *
 * @ODM\EmbeddedDocument
 *
 * @author      Johannes Knauft
 * @package     Perna\Documents
 */
class CalenderModule extends Module {

    /**
     * @ODM\Field(
     *     name = "calenderIds",
     *     type = "collection"
     * )
     * @var string[] array of calender ids
     */
    protected $calenderIds;

    /**
     * @return string[]
     */
    public function getCalenderIds()
    {
        return $this->calenderIds;
    }

    /**
     * @param string[] $calenderIds
     */
    public function setCalenderIds($calenderIds)
    {
        $this->calenderIds = $calenderIds;
    }
}