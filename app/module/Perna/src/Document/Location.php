<?php

namespace Perna\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Document representing a Geo-Location
 *
 * @ODM\EmbeddedDocument
 *
 * @author      Jannik Portz
 * @package     Perna\Document
 */
class Location {

	/**
	 * @ODM\Field(
	 *   name="latitude",
	 *   type="float"
	 * )
	 *
	 * @var       float
	 */
	protected $latitude;

	/**
	 * @ODM\Field(
	 *   name="longitude",
	 *   type="float"
	 * )
	 *
	 * @var       float
	 */
	protected $longitude;

}