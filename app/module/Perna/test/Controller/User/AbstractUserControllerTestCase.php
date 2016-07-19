<?php

namespace Perna\Test\Controller\User;

use Perna\Test\Controller\AbstractControllerTestCase;
use Zend\Crypt\Password\Bcrypt;

class AbstractUserControllerTestCase extends AbstractControllerTestCase {

	protected function generatePasswordHash ( string $password ) : string {
		$bcrypt = new Bcrypt();
		return $bcrypt->create( $password );
	}
}