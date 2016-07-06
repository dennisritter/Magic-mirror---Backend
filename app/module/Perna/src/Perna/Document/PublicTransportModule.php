<?php

namespace Perna\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Swagger\Annotations as SWG;

/**
 * Document-Class for Public Transport
 *
 * @ODM\EmbeddedDocument
 *
 * @author      Johannes Knauft
 * @package     Perna\Documents
 */
class PublicTransportModule extends Module {

    public function __construct() {
        parent::__construct();
        $this->type = "publicTransport";
    }

    /**
     * @ODM\Field(
     *     name = "stationId",
     *     type = "string"
     * )
     * @var string of StationId
     */
    protected $stationId;

    /**
     * @ODM\Field(
     *     name = "stationName",
     *     type = "string"
     * )
     * @var string of StationId
     */
    protected $stationName;

    /**
     * @ODM\Field(
     *     name = "stationName",
     *     type = "collection"
     * )
     * @var array of Products
     */
    protected $products;

    /**
     * @return string
     */
    public function getStationId()
    {
        return $this->stationId;
    }

    /**
     * @param string $stationId
     */
    public function setStationId($stationId)
    {
        $this->stationId = $stationId;
    }

    /**
     * @return string
     */
    public function getStationName()
    {
        return $this->stationName;
    }

    /**
     * @param string $stationName
     */
    public function setStationName($stationName)
    {
        $this->stationName = $stationName;
    }

    /**
     * @return array
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param array $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }
}

