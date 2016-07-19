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


    /**
     * @ODM\Field(
     *     name = "viewType",
     *     type = "string"
     * )
     * @var string $viewType
     */
    protected $viewType;

    public function __construct() {
        parent::__construct();
        $this->type = "time";
    }

    /**
     * @return mixed
     */
    public function getViewType()
    {
        return $this->viewType;
    }

    /**
     * @param mixed $viewType
     */
    public function setViewType($viewType)
    {
        $this->viewType = $viewType;
    }
}