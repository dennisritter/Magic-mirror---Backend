<?php

namespace Perna\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Document\User;

class ModuleService {

    protected $documentManager;

    public function __construct( DocumentManager $documentManager) {
        $this->documentManager = $documentManager;
    }

    public function getModules( $user ) : array {
        /** @var $user User */
        return $user->getModules();
    }

    public function addModule ( $user, $data ) {
        
    }
}