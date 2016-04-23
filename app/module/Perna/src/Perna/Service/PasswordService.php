<?php

namespace Perna\Service;

use Perna\Document\User;
use Zend\Crypt\Password\Bcrypt;

class PasswordService {

	/**
	 * @var       Bcrypt
	 */
	protected $bcrypt;

	public function __construct () {
		$this->bcrypt = new Bcrypt();
	}

	public function setUserPassword ( User $user, string $password ) : User {
		$user->setPassword( $this->bcrypt->create( $password ) );
		return $user;
	}

	public function passwordMatches ( User $user, string $password ) : boolean {
		return $this->bcrypt->verify( $password, $user->getPassword() );
	}
}