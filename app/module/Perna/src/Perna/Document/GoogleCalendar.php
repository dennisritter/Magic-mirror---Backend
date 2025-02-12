<?php

namespace Perna\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Swagger\Annotations as SWG;

/**
 * Document representing a Google Calendar
 *
 * @SWG\Definition(
 *   @SWG\Xml(name="GoogleCalendar")
 * )
 *
 * @ODM\EmbeddedDocument
 *
 * @author      Jannik Portz
 * @package     Perna\Document
 */
class GoogleCalendar {

	/**
	 * The ID of the Google Calendar
	 * @SWG\Property()
	 * @ODM\Id(
	 *   strategy="NONE",
	 *   type="string"
	 * )
	 * @var       string
	 */
	protected $id;

	/**
	 * The Access Role for the Calendar
	 * @SWG\Property()
	 * @ODM\Field()
	 * @var       string
	 */
	protected $accessRole;

	/**
	 * The color of the calendar as HEX RGB color string
	 * @SWG\Property()
	 * @ODM\Field()
	 * @var       string
	 */
	protected $color;

	/**
	 * The title of the calendar
	 * @SWG\Property()
	 * @ODM\Field()
	 * @var       string
	 */
	protected $summary;

	/**
	 * The description of the calendar
	 * @SWG\Property()
	 * @ODM\Field()
	 * @var       string
	 */
	protected $description;

	/**
	 * Whether the calendar is visible for the user in Google Calendar UI
	 * @SWG\Property()
	 * @ODM\Field(type="boolean")
	 * @var       bool
	 */
	protected $selected;

	/**
	 * Whether this is the User's primary calendar.
	 * There may only be one primary calendar for the user.
	 * @SWG\Property()
	 * @ODM\Field(type="boolean")
	 * @var       bool
	 */
	protected $primary;

	/**
	 * The GoogleEventCache caching the events for this calendar
	 * @ODM\ReferenceOne(
	 *   targetDocument="GoogleEventCache",
	 *   simple=true
	 * )
	 * @var       GoogleEventCache
	 */
	protected $eventCache;

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
	public function getAccessRole() {
		return $this->accessRole;
	}

	/**
	 * @param string $accessRole
	 */
	public function setAccessRole( $accessRole ) {
		$this->accessRole = $accessRole;
	}

	/**
	 * @return string
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * @param string $color
	 */
	public function setColor( $color ) {
		$this->color = $color;
	}

	/**
	 * @return string
	 */
	public function getSummary() {
		return $this->summary;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription( $description ) {
		$this->description = $description;
	}

	/**
	 * @param string $summary
	 */
	public function setSummary( $summary ) {
		$this->summary = $summary;
	}

	/**
	 * @return boolean
	 */
	public function getSelected() {
		return $this->selected;
	}

	/**
	 * @param boolean $selected
	 */
	public function setSelected( $selected ) {
		$this->selected = $selected;
	}

	/**
	 * @return boolean
	 */
	public function getPrimary() {
		return $this->primary;
	}

	/**
	 * @param boolean $primary
	 */
	public function setPrimary( $primary ) {
		$this->primary = $primary;
	}

	/**
	 * @return GoogleEventCache
	 */
	public function getEventCache() {
		return $this->eventCache;
	}

	/**
	 * @param GoogleEventCache $eventCache
	 */
	public function setEventCache( $eventCache ) {
		$this->eventCache = $eventCache;
	}
}