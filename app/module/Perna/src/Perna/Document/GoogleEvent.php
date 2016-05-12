<?php

namespace Perna\Document;

/**
 * Document representing a Google Calendar Event
 *
 * @author      Jannik Portz
 * @package     Perna\Document
 */
class GoogleEvent {

	/**
	 * The event Id
	 * @var       string
	 */
	protected $id;

	/**
	 * The etag of the current event state
	 * @var       string
	 */
	protected $etag;

	/**
	 * The event description
	 * @var       string
	 */
	protected $description;

	/**
	 * The event location as textual description
	 * @var       string
	 */
	protected $location;

	/**
	 * The event transparency. Possible values are 'opaque' and 'transparent'.
	 * Indicates whether the event actually blocks time in the calendar.
	 * @var       string
	 */
	protected $transparency;

	/**
	 * The last time the event has been updated.
	 * @var       \DateTime
	 */
	protected $updated;

	/**
	 * The event summary / title
	 * @var       string
	 */
	protected $summary;

	/**
	 * Names of all event attendees
	 * @var       string[]
	 */
	protected $attendees;

	/**
	 * The start date/time of the event
	 * @var       \DateTime
	 */
	protected $startTime;

	/**
	 * The end date/time of the event
	 * @var       \DateTime
	 */
	protected $endTime;

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
}