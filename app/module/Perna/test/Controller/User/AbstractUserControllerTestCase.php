<?php

namespace Perna\Test\Controller\User;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Perna\Document\User;
use Perna\Test\Controller\AbstractControllerTestCase;
use Zend\Crypt\Password\Bcrypt;

class AbstractUserControllerTestCase extends AbstractControllerTestCase {

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $documentManager;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $documentRepository;

	public function setUp () {
		parent::setUp();

		$repoMock = $this->getMockBuilder( DocumentRepository::class )->disableOriginalConstructor()->getMock();

		$dmMock = $this->getMockBuilder( DocumentManager::class )->disableOriginalConstructor()->getMock();
		$dmMock->method('getRepository')
		       ->with( $this->equalTo( User::class ) )
		       ->willReturn( $repoMock );

		$this->documentManager = $dmMock;
		$this->documentRepository = $repoMock;

		$sm = $this->getApplicationServiceLocator();
		$sm->setAllowOverride( true );
		$sm->setService( DocumentManager::class, $dmMock );
	}

	protected function generatePasswordHash ( string $password ) : string {
		$bcrypt = new Bcrypt();
		return $bcrypt->create( $password );
	}
}