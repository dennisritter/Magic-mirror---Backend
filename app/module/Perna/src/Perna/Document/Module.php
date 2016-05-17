<?php

namespace Perna\Document;


use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;


/**
 * Document-Class for Modules
 *
 * @ODM\EmbeddedDocument
 * 
 * @author      Johannes Knauft
 * @package     Perna\Documents
 */
abstract class Module {

    /**
     * @ODM\Id(
     *   name="_id",
     *   strategy="AUTO"
     * )
     * @var string
     */
    protected $id;

    /**
     * @ODM\Field(
     *     name = "type",
     *     type = "string"
     * )
     * @var string type of a module
     */
    protected $type;
    
    /**
     * @ODM\Field(
     *     name = "width",
     *     type = "int"
     * )
     * @var int width of a module
     */
    protected $width;

    /**
     * @ODM\Field(
     *     name = "height",
     *     type = "int"
     * )
     * @var int height of a module
     */
    protected $height;

    /**
     * @ODM\Field(
     *     name = "xPosition",
     *     type = "int"
     * )
     * @var int x-position of a module
     */
    protected $xPosition;

    /**
     * @ODM\Field(
     *     name = "yPosition",
     *     type = "int"
     * )
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
}