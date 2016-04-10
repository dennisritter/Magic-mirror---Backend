<?php

namespace Perna\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Document class for a user
 *
 * @ODM\Document(collection="users")
 *
 * @author      Jannik Portz
 * @package     Perna\Document
 */
class User {

	/**
	 * @ODM\Id(strategy="AUTO")
	 * @var       string
	 */
	protected $id;

	/**
	 * @ODM\Field(type="string")
	 * @var       string
	 */
	protected $email;

	/**
	 * @ODM\Field(type="string")
	 * @var       string
	 */
	protected $password;

	/**
	 * @ODM\Field(type="string")
	 * @var       string
	 */
	protected $firstName;

	/**
	 * @ODM\Field(type="string")
	 * @var       string
	 */
	protected $lastName;

}