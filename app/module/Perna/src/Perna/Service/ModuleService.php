<?php

namespace Perna\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\PersistentCollection;
use Perna\Document\Module;
use Perna\Document\User;

class ModuleService {

    protected $documentManager;

    public function __construct( DocumentManager $documentManager) {
        $this->documentManager = $documentManager;
    }

    public function getModules( User $user ) : array {
        /** @var PersistentCollection $modules */
        $modules = $user->getModules();
        return $modules->toArray();
    }

    public function addModule ( User $user, Module $module ) {
        /** @var PersistentCollection $modules */
        $modules = $user->getModules();
        $modules->add( $module );
        $this->documentManager->flush();
    }

    public function removeModule( User $user, string $id ) {
        echo $id;
        /** @var PersistentCollection $modules */
        $modules = $user->getModules();
        
        foreach ( $modules as $module){
            if($module->getId() == $id){
                $modules->remove($module);
            }
        }
        $user->setModules($modules);
        $this->documentManager->flush();
    }
}