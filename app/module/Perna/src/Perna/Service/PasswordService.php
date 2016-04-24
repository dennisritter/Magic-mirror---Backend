<?php

namespace Perna\Service;

use Perna\Document\User;
use Zend\Crypt\Password\Bcrypt;

/**
 * Service for Password-Operations
 *
 * @author      Jannik Portz
 * @package     Perna\Service
 */
class PasswordService {

	/**
	 * ZF Bcrypt instance
	 * @var       Bcrypt
	 */
	protected $bcrypt;

	public function __construct () {
		$this->bcrypt = new Bcrypt();
	}

	/**
	 * Generates salted password hash and sets the password in the User object
	 * @param     User      $user     The user whose password to set
	 * @param     string    $password The password in plain text
	 * @return    User                The User instance
	 */
	public function setUserPassword ( User $user, string $password ) : User {
		$user->setPassword( $this->bcrypt->create( $password ) );
		return $user;
	}

	/**
	 * Checks whether the provided password matches the password hash stored in the User object
	 * @param     User      $user     The user whose password to verify
	 * @param     string    $password The password to check against the password hash stored in the User object
	 * @return    bool                Whether the provided password matches the password hash
	 */
	public function passwordMatches ( User $user, string $password ) : bool {
		return $this->bcrypt->verify( $password, $user->getPassword() );
	}
}