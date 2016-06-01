<?php

namespace Perna\Document;


use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Perna\Service\GUIDGenerator;
use Swagger\Annotations as SWG;


/**
 * Document-Class for Modules
 *
 * @ODM\EmbeddedDocument
 * @ODM\MappedSuperclass
 * @ODM\DiscriminatorField("moduleType")
 * @ODM\DiscriminatorMap({
 *     "calendar"="CalendarModule"
 *   })
 * @SWG\Definition(
 *   required={"id", "height", "width", "xPosition", "yPosition", "type"},
 *   @SWG\Xml(name="Module")
 * )
 * 
 * @author      Johannes Knauft
 * @package     Perna\Documents
 */
abstract class Module {

    /**
     * @ODM\Field(
     *     name = "id",
     *     type = "string"
     * )
     * @SWG\Property(property="id", type="string")
     * @var string
     */
    protected $id;

    /**
     * @ODM\Field(
     *     name = "type",
     *     type = "string"
     * )
     * @SWG\Property(property="type", type="string")
     * @var string type of a module
     */
    protected $type;
    
    /**
     * @ODM\Field(
     *     name = "width",
     *     type = "int"
     * )
     * @SWG\Property()
     * @var int width of a module
     */
    protected $width;

    /**
     * @ODM\Field(
     *     name = "height",
     *     type = "int"
     * )
     * @SWG\Property()
     * @var int height of a module
     */
    protected $height;

    /**
     * @ODM\Field(
     *     name = "xPosition",
     *     type = "int"
     * )
     * @SWG\Property()
     * @var int x-position of a module
     */
    protected $xPosition;

    /**
     * @ODM\Field(
     *     name = "yPosition",
     *     type = "int"
     * )
     * @SWG\Property()
     * @var int y-position of a module
     */
    protected $yPosition;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getXPosition()
    {
        return $this->xPosition;
    }

    /**
     * @param int $xPosition
     */
    public function setXPosition($xPosition)
    {
        $this->xPosition = $xPosition;
    }

    /**
     * @return int
     */
    public function getYPosition()
    {
        return $this->yPosition;
    }

    /**
     * @param int $yPosition
     */
    public function setYPosition($yPosition)
    {
        $this->yPosition = $yPosition;
    }

    public function __construct() {
        $guid = new GUIDGenerator();
        $this->id = $guid->generateGUID();
    }
}