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
        /** @var PersistentCollection $modules */
        $modules = $user->getModules();
        
        foreach ( $modules as $module){
            if($module->getId() == $id){
                $modules->removeElement( $module );
            }
        }
        $this->documentManager->flush();
    }

    public function getModuleById( User $user, string $id) : Module{
        /** @var PersistentCollection $modules */
        $modules = $user->getModules();
        $modules->toArray();
        foreach ( $modules as $module){
            if($module->getId() == $id){
                return $module;
            }
        }
        return null;
    }

    public function setModule( User $user, string $id, Module $moduledata ){
        /** @var Module $module */
        foreach (array_keys())
        /** @var PersistentCollection $modules */
        $modules = $user->getModules();
        $modules->removeElement()
        $this->documentManager->flush();
        return $module;
    }

    public function setModules( array $modules, User $user ) {
        $user->setModules( $modules );
        return $user->getModules();
    }
}