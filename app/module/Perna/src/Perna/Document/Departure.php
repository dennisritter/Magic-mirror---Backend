<?php

namespace Perna\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Swagger\Annotations as SWG;

/**
 * Document representing a departure at a Station
 *
 * @ODM\EmbeddedDocument()
 *
 * @SWG\Definition(
 *   @SWG\Xml(name="Departure")
 * )
 *
 * @author      Jannik Portz
 * @package     Perna\Document
 */
class Departure {

	/**
	 * Identifier for the product
	 *
	 * @ODM\Field(type="string")
	 * @SWG\Property()
	 *
	 * @var       string
	 */
	protected $product;

	/**
	 * The train direction / departure station
	 *
	 * @ODM\Field(type="string")
	 * @SWG\Property()
	 *
	 * @var       string
	 */
	protected $direction;

	/**
	 * The name of the train line
	 *
	 * @ODM\Field(type="string")
	 * @SWG\Property()
	 *
	 * @var       string
	 */
	protected $name;

	/**
	 * The date and time when the train will actually arrive
	 *
	 * @ODM\Field(type="date")
	 * @SWG\Property(type="string", format="date-time")
	 *
	 * @var       \DateTime
	 */
	protected $realTime;

	/**
	 * The date and time when the departure was scheduled
	 *
	 * @ODM\Field(type="date")
	 * @SWG\Property(type="string", format="date-time")
	 *
	 * @var       \DateTime
	 */
	protected $scheduledTime;

	/**
	 * @return string
	 */
	public function getProduct() {
		return $this->product;
	}

	/**
	 * @param string $product
	 */
	public function setProduct( $product ) {
		$this->product = $product;
	}

	/**
	 * @return string
	 */
	public function getDirection() {
		return $this->direction;
	}

	/**
	 * @param string $direction
	 */
	public function setDirection( $direction ) {
		$this->direction = $direction;
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
	 * @return \DateTime
	 */
	public function getRealTime() {
		return $this->realTime;
	}

	/**
	 * @param \DateTime $realTime
	 */
	public function setRealTime( $realTime ) {
		$this->realTime = $realTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getScheduledTime() {
		return $this->scheduledTime;
	}

	/**
	 * @param \DateTime $scheduledTime
	 */
	public function setScheduledTime( $scheduledTime ) {
		$this->scheduledTime = $scheduledTime;
	}
}