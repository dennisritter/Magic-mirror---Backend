<?php

namespace Perna\Document;

class Station {

	/**
	 * The VBB Id
	 * @var       string
	 */
	protected $id;

	/**
	 * The external ID of the station
	 * @var       string
	 */
	protected $extId;

	/**
	 * The name of the station
	 * @var       string
	 */
	protected $name;

	/**
	 * The Geo-Location of the Station as [lat, lng] array
	 * @var       array
	 */
	protected $location;

	/**
	 * Array of products that are available on this station
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