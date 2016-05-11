<?php

namespace Perna\Document;

/**
 * Document representing a Google Calendar
 *
 * @author      Jannik Portz
 * @package     Perna\Document
 */
class GoogleCalendar {

	/**
	 * The ID of the Google Calendar
	 * @var       string
	 */
	protected $id;

	/**
	 * The Access Role for the Calendar
	 * @var       string
	 */
	protected $accessRole;

	/**
	 * The color of the calendar as HEX RGB color string
	 * @var       string
	 */
	protected $color;

	/**
	 * The title of the calendar
	 * @var       string
	 */
	protected $title;

	/**
	 * The description of the calendar
	 * @var       string
	 */
	protected $description;

	/**
	 * Whether the calendar is visible for the user in Google Calendar UI
	 * @var       bool
	 */
	protected $selected;

	/**
	 * Whether this is the User's primary calendar.
	 * There may only be one primary calendar for the user.
	 * @var       bool
	 */
	protected $primary;

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
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( $title ) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getString() {
		return $this->string;
	}

	/**
	 * @param string $string
	 */
	public function setString( $string ) {
		$this->string = $string;
	}

	/**
	 * @return boolean
	 */
	public function isSelected() {
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
	public function isPrimary() {
		return $this->primary;
	}

	/**
	 * @param boolean $primary
	 */
	public function setPrimary( $primary ) {
		$this->primary = $primary;
	}
}