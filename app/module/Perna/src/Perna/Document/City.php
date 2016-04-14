<?php

namespace Perna\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Document representing a City
 *
 * @ODM\Document(
 *   db="perna",
 *   collection="cities"
 * )
 * @ODM\Index(keys={"location"="2dsphere"})
 *
 * @author      Jannik Portz
 * @package     Perna\Document
 */
class City {

	/**
	 * The primary identifier.
	 * According to Open Weather Map City Id.
	 *
	 * @ODM\Id(
	 *   name="_id",
	 *   strategy="NONE",
	 *   type="int"
	 * )
	 * @var       int
	 */
	protected $id;

	/**
	 * @ODM\Field(
	 *   name="name",
	 *   type="string"
	 * )
	 *
	 * @var       string
	 */
	protected $name;

	/**
	 * @ODM\Field(
	 *   name="countryCode",
	 *   type="string"
	 * )
	 * @var       string
	 */
	protected $countryCode;

	/**
	 * @ODM\Field(
	 *   name="location",
	 *   type="collection"
	 * )
	 *
	 * @var       array
	 */
	protected $location;

	/**
	 * @return int
	 */
	public function getId() : int {
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId( int $id ) {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getName() : string {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getCountryCode() : string {
		return $this->countryCode;
	}

	/**
	 * @param string $countryCode
	 */
	public function setCountryCode( string $countryCode ) {
		$this->countryCode = $countryCode;
	}

	/**
	 * @return array
	 */
	public function getLocation() : array {
		return $this->location;
	}

	/**
	 * @param array $location
	 */
	public function setLocation( array $location ) {
		$this->location = $location;
	}
}