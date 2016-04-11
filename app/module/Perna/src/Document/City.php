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
	 * The primary identifier. According to Open Weather Map City Id.
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

}