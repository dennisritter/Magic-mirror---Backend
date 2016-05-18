<?php

namespace Perna\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Perna\Document\CalendarModule;
use Perna\Document\User;

class ModuleService {

    protected $documentManager;

    public function __construct( DocumentManager $documentManager) {
        $this->documentManager = $documentManager;
    }

    public function getModules( $user ) : array {
        /** @var $user User */
        $modules = $user->getModules();
        $obj = [];
        foreach ($modules as $value)
            array_push($obj, $value);
        return $obj;
    }

    public function addModule ( $user, $module ) {
        /** @var User $user  */
        $modules = $user->getModules();
        $module_array = [];
        array_push($module_array, $module);
        $user->setModules( $module_array );
        $this->documentManager->flush();
    }
}