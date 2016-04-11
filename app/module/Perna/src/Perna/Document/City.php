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
	 * @ODM\EmbedOne(
	 *   targetDocument="Location"
	 * )
	 *
	 * @var       Location
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
	 * @return Location
	 */
	public function getLocation() : Location {
		return $this->location;
	}

	/**
	 * @param Location $location
	 */
	public function setLocation( Location $location ) {
		$this->location = $location;
	}

}