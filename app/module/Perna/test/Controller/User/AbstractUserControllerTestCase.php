<?php

namespace Perna\Test\Controller\User;

use Perna\Test\Controller\AbstractControllerTestCase;
use Zend\Crypt\Password\Bcrypt;

class AbstractUserControllerTestCase extends AbstractControllerTestCase {

	const DUMMY_ACCESS_TOKEN = 'C57A25FF-A9E9-5870-2E2A-6A3156EFDB22';

	protected function generatePasswordHash ( string $password ) : string {
		$bcrypt = new Bcrypt();
		return $bcrypt->create( $password );
	}
}