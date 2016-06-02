<?php

namespace Perna\Test\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AbstractControllerTestCase extends AbstractHttpControllerTestCase {

	public function setUp () {
		$this->setApplicationConfig( include __DIR__ . '/../../../../config/application.config.php' );
		parent::setUp();
	}

}