<?php

namespace Perna\Document;


use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;


/**
 * Document-Class for Modules
 *
 * @ODM\EmbeddedDocument
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField("type")
 * @ODM\DiscriminatorMap({"calender"="CalenderModule"})
 * 
 * @author      Johannes Knauft
 * @package     Perna\Documents
 */
abstract class Module {
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
     * @ODM\Field(
     *     name = "settings",
     *     type = "collection"
     * )
     * @var  mixed of settings
     */
    protected $settings;

    protected abstract function addSetting ( $keyValueArray );

    protected abstract function removeSetting ( $key );

    protected abstract function setSetting ( $key, $value );
}