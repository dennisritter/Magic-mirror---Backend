<?php

namespace Perna\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Swagger\Annotations as SWG;

/**
 * @ODM\Document(
 *  db="perna",
 *  collection="users"
 * )
 * @SWG\Definition(
 *   required={"id", "firstName", "lastName", "email"},
 *   @SWG\Xml(name="User")
 * )
 */
class User {

	/**
	 * @ODM\Id(
	 *   name="_id",
	 *   strategy="AUTO"
	 * )
	 *
	 * @SWG\Property(property="id", type="string")
	 * @var string
	 */
	protected $id;

	/**
	 * @ODM\Field(
	 *   name="firstName",
	 *   type="string"
	 * )
	 * @SWG\Property(property="firstName", type="string")
	 * @var string
	 */
	protected $firstName;

	/**
	 * @ODM\Field(
	 *   name="lastName",
	 *   type="string"
	 * )
	 * @SWG\Property(property="lastName", type="string")
	 * @var string
	 */
	protected $lastName;


	/**
	 * @ODM\Field(
	 *   name="email",
	 *   type="string"
	 * )
	 * @SWG\Property(property="email", type="string")
	 * @var string
	 */
	protected $email;


	/**
	 * @ODM\Field(
	 *   name="password",
	 *   type="string"
	 * )
	 * @var string
	 */
	protected $password;

	/**
	 * @ODM\Field(
	 *   name="lastLogin",
	 *   type="date"
	 * )
	 *
	 * @var \DateTime
	 */
	protected $lastLogin;

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
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * @param string $firstName
	 */
	public function setFirstName( $firstName ) {
		$this->firstName = $firstName;
	}

	/**
	 * @return string
	 */
	public function getLastName() {
		return $this->lastName;
	}

	/**
	 * @param string $lastName
	 */
	public function setLastName( $lastName ) {
		$this->lastName = $lastName;
	}

	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail( $email ) {
		$this->email = $email;
	}

	/**
	 * @return mixed
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param mixed $password
	 */
	public function setPassword( $password ) {
		$this->password = $password;
	}

	/**
	 * @return mixed
	 */
	public function getLastLogin() {
		return $this->lastLogin;
	}

	/**
	 * @param mixed $lastLogin
	 */
	public function setLastLogin( $lastLogin ) {
		$this->lastLogin = $lastLogin;
	}
}