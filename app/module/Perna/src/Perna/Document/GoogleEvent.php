<?php

namespace Perna\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Swagger\Annotations as SWG;

/**
 * Document representing a Google Calendar Event
 *
 * @SWG\Definition(
 *   required={"id", "description", "location", "transparency", "updated", "summary", "attendees", "startTime", "endTime"},
 *   @SWG\Xml(name="GoogleEvent")
 * )
 *
 * @ODM\EmbeddedDocument
 *
 * @author      Jannik Portz
 * @package     Perna\Document
 */
class GoogleEvent {

	/**
	 * The event Id
	 * @SWG\Property()
	 * @ODM\Id(
	 *   strategy="NONE",
	 *   type="string"
	 * )
	 * @var       string
	 */
	protected $id;

	/**
	 * The etag of the current event state
	 * @ODM\Field()
	 * @var       string
	 */
	protected $etag;

	/**
	 * The event description
	 * @SWG\Property()
	 * @ODM\Field()
	 * @var       string
	 */
	protected $description;

	/**
	 * The event location as textual description
	 * @SWG\Property()
	 * @ODM\Field()
	 * @var       string
	 */
	protected $location;

	/**
	 * The event transparency. Possible values are 'opaque' and 'transparent'.
	 * Indicates whether the event actually blocks time in the calendar.
	 * @SWG\Property()
	 * @ODM\Field()
	 * @var       string
	 */
	protected $transparency;

	/**
	 * The last time the event has been updated.
	 * @SWG\Property()
	 * @ODM\Field(type="date")
	 * @var       \DateTime
	 */
	protected $updated;

	/**
	 * The event summary / title
	 * @SWG\Property()
	 * @ODM\Field()
	 * @var       string
	 */
	protected $summary;

	/**
	 * Names of all event attendees
	 * @SWG\Property(property="attendees", type="array", @SWG\Items(type="string"))
	 * @ODM\Field(type="collection")
	 * @var       string[]
	 */
	protected $attendees;

	/**
	 * The start date/time of the event
	 * @SWG\Property()
	 * @ODM\Field(type="date")
	 * @var       \DateTime
	 */
	protected $startTime;

	/**
	 * The end date/time of the event
	 * @SWG\Property()
	 * @ODM\Field(type="date")
	 * @var       \DateTime
	 */
	protected $endTime;

	/**
	 * The id of the calendar that the event belongs to
	 * @SWG\Property()
	 * @ODM\Field()
	 * @var       string
	 */
	protected $calendarId;

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
	public function getEtag() {
		return $this->etag;
	}

	/**
	 * @param string $etag
	 */
	public function setEtag( $etag ) {
		$this->etag = $etag;
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
	 * @return string
	 */
	public function getLocation() {
		return $this->location;
	}

	/**
	 * @param string $location
	 */
	public function setLocation( $location ) {
		$this->location = $location;
	}

	/**
	 * @return string
	 */
	public function getTransparency() {
		return $this->transparency;
	}

	/**
	 * @param string $transparency
	 */
	public function setTransparency( $transparency ) {
		$this->transparency = $transparency;
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdated() {
		return $this->updated;
	}

	/**
	 * @param \DateTime $updated
	 */
	public function setUpdated( $updated ) {
		$this->updated = $updated;
	}

	/**
	 * @return string
	 */
	public function getSummary() {
		return $this->summary;
	}

	/**
	 * @param string $summary
	 */
	public function setSummary( $summary ) {
		$this->summary = $summary;
	}

	/**
	 * @return \string[]
	 */
	public function getAttendees() {
		return $this->attendees;
	}

	/**
	 * @param \string[] $attendees
	 */
	public function setAttendees( $attendees ) {
		$this->attendees = $attendees;
	}

	/**
	 * @return \DateTime
	 */
	public function getStartTime() {
		return $this->startTime;
	}

	/**
	 * @param \DateTime $startTime
	 */
	public function setStartTime( $startTime ) {
		$this->startTime = $startTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getEndTime() {
		return $this->endTime;
	}

	/**
	 * @param \DateTime $endTime
	 */
	public function setEndTime( $endTime ) {
		$this->endTime = $endTime;
	}

	/**
	 * @return string
	 */
	public function getCalendarId() {
		return $this->calendarId;
	}

	/**
	 * @param string $calendarId
	 */
	public function setCalendarId( $calendarId ) {
		$this->calendarId = $calendarId;
	}
}