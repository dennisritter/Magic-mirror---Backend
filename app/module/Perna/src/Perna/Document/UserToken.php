<?php

namespace Perna\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Swagger\Annotations as SWG;

/**
 * @ODM\MappedSuperclass()
 * @ODM\InheritanceType("COLLECTION_PER_CLASS")
 *
 * @SWG\Definition(
 *   @SWG\Xml(name="UserToken")
 * )
 */
abstract class UserToken {
	
	/**
	 * @ODM\Id(
	 *   name="token",
	 *   strategy="NONE",
	 *   type="string"
	 * )
	 * @SWG\Property(property="token", type="string")
	 * @var string
	 */
	protected $token;

	/**
	 * @ODM\ReferenceOne(targetDocument="User", simple=true)
	 * @var User
	 */
	protected $user;

	/**
	 * @ODM\Field(
	 *   name="expires",
	 *   type="boolean"
	 * )
	 * @SWG\Property(property="expires", type="boolean")
	 * @var boolean
	 */
	protected $expires;

	/**
	 * @ODM\Field(
	 *   name="expirationDate",
	 *   type="date"
	 * )
	 * @SWG\Property(property="expirationDate", type="string", format="date-time")
	 * @var \DateTime
	 */
	protected $expirationDate;

	/**
	 * @return string
	 */
	public function getToken() {
		return $this->token;
	}

	/**
	 * @param string $token
	 */
	public function setToken( $token ) {
		$this->token = $token;
	}

	/**
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param User $user
	 */
	public function setUser( $user ) {
		$this->user = $user;
	}

	/**
	 * @return boolean
	 */
	public function getExpires() {
		return $this->expires;
	}

	/**
	 * @param boolean $expires
	 */
	public function setExpires( $expires ) {
		$this->expires = $expires;
	}

	/**
	 * @return \DateTime
	 */
	public function getExpirationDate() {
		return $this->expirationDate;
	}

	/**
	 * @param \DateTime $expirationDate
	 */
	public function setExpirationDate( $expirationDate ) {
		$this->expirationDate = $expirationDate;
	}
}