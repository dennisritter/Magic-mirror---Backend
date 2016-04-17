<?php

namespace Perna\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Swagger\Annotations as SWG;

/**
 * Document representing a City
 *
 * @ODM\Document(
 *   db="perna",
 *   collection="cities"
 * )
 * @ODM\Index(keys={"location"="2dsphere"})
 *
 * @SWG\Definition(
 *   required={"id", "name", "countryCode", "location"},
 *   @SWG\Xml(
 *    name="City"
 *   )
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
	 *
	 * @SWG\Property(property="id", type="int")
	 *
	 * @var       int
	 */
	protected $id;

	/**
	 * The name of the city in native language
	 *
	 * @ODM\Field(
	 *   name="name",
	 *   type="string"
	 * )
	 *
	 * @SWG\Property(property="name", type="string")
	 *
	 * @var       string
	 */
	protected $name;

	/**
	 * Two-character country identifier
	 *
	 * @ODM\Field(
	 *   name="countryCode",
	 *   type="string"
	 * )
	 *
	 * @SWG\Property()
	 *
	 * @var       string
	 */
	protected $countryCode;

	/**
	 * Array containing Geo-Coordinates.
	 * [latitude, longitude]
	 *
	 * @ODM\Field(
	 *   name="location",
	 *   type="collection"
	 * )
	 *
	 * @SWG\Property(
	 *    property="location",
	 *    type="array",
	 *    @SWG\Items(type="float")
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