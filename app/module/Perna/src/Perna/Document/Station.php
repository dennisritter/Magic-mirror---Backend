<?php

namespace Perna\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Swagger\Annotations as SWG;

/**
 * Document representing a train station
 *
 * @ODM\Document(
 *   db="perna",
 *   collection="stations"
 * )
 *
 * @SWG\Definition(
 *   @SWG\Xml(name="Station")
 * )
 *
 * @author      Jannik Portz
 * @package     Perna\Document
 */
class Station {

	/**
	 * The VBB Id
	 *
	 * @ODM\Field(type="string")
	 *
	 * @var       string
	 */
	protected $id;

	/**
	 * The external ID of the station
	 *
	 * @ODM\Field(type="string")
	 * @SWG\Property(property="id", type="string")
	 *
	 * @var       string
	 */
	protected $extId;

	/**
	 * The name of the station
	 *
	 * @ODM\Field(type="string")
	 * @SWG\Property(type="string")
	 *
	 * @var       string
	 */
	protected $name;

	/**
	 * The Geo-Location of the Station as [lat, lng] array
	 *
	 * @ODM\Field(type="collection")
	 * @SWG\Property(
	 *   type="array"
	 *   @SWG\Items(type="number"),
	 *   default={52.5451160, 13.3552320}
	 * )
	 *
	 * @var       array
	 */
	protected $location;

	/**
	 * Array of product-identifiers that are available on this station
	 *
	 * @ODM\Field(type="collection")
	 * @SWG\Property(
	 *   type="array",
	 *   @SWG\Items(type="string")
	 * )
	 *
	 * @var       array
	 */
	protected $products;

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getExtId() {
		return $this->extId;
	}

	/**
	 * @param string $extId
	 */
	public function setExtId( $extId ) {
		$this->extId = $extId;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name ) {
		$this->name = $name;
	}

	/**
	 * @return array
	 */
	public function getLocation() {
		return $this->location;
	}

	/**
	 * @param array $location
	 */
	public function setLocation( $location ) {
		$this->location = $location;
	}

	/**
	 * @return array
	 */
	public function getProducts() {
		return $this->products;
	}

	/**
	 * @param array $products
	 */
	public function setProducts( $products ) {
		$this->products = $products;
	}
}