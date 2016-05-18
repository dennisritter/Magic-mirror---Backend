<?php

namespace Perna\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * GoogleEventCache contains cached events and meta information on the caching of the events of one specific calendar
 *
 * @ODM\EmbeddedDocument()
 *
 * @author      Jannik Portz
 * @package     Perna\Document
 */
class GoogleEventCache {

	/**
	 * The token identifying the current watch Session
	 * @ODM\Id(
	 *   type="string",
	 *   strategy="NONE"
	 * )
	 * @var       string
	 */
	protected $watchSessionToken;

	/**
	 * The expiration date/time of the current watch session
	 * @ODM\Field(type="date")
	 * @var       \DateTime
	 */
	protected $watchSessionExpiration;

	/**
	 * The currently cached events
	 * @ODM\EmbedMany(targetDocument="GoogleEvent")
	 * @var       GoogleEvent[]
	 */
	protected $events;

	/**
	 * The date/time of the last update in the cache
	 * @var       \DateTime
	 */
	protected $updated;

	/**
	 * The date/time when the cache has been created
	 * @var       \DateTime
	 */
	protected $created;

	/**
	 * @return string
	 */
	public function getWatchSessionToken() {
		return $this->watchSessionToken;
	}

	/**
	 * @param string $watchSessionToken
	 */
	public function setWatchSessionToken( $watchSessionToken ) {
		$this->watchSessionToken = $watchSessionToken;
	}

	/**
	 * @return \DateTime
	 */
	public function getWatchSessionExpiration() {
		return $this->watchSessionExpiration;
	}

	/**
	 * @param \DateTime $watchSessionExpiration
	 */
	public function setWatchSessionExpiration( $watchSessionExpiration ) {
		$this->watchSessionExpiration = $watchSessionExpiration;
	}

	/**
	 * @return GoogleEvent[]
	 */
	public function getEvents() {
		return $this->events;
	}

	/**
	 * @param GoogleEvent[] $events
	 */
	public function setEvents( $events ) {
		$this->events = $events;
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
	 * @return \DateTime
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * @param \DateTime $created
	 */
	public function setCreated( $created ) {
		$this->created = $created;
	}
}