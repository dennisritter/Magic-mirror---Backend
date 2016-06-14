<?php

namespace Perna\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\PersistentCollection;
use Perna\Document\CalendarModule;
use Perna\Document\Module;
use Perna\Document\User;
use Perna\Document\WeatherModule;
use ZfrRest\Http\Exception\Client\NotFoundException;

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

        foreach ( $modules as $module ){
            if ( $module->getId() == $id ) {
                $modules->removeElement( $module );
                $this->documentManager->flush();
                return;
            }
        }

        throw new NotFoundException("A module with id {$id} is not among the user's saved modules.");
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

        throw new NotFoundException("A module with id {$id} is not among the user's saved modules.");
    }

    public function setModule( User $user, string $id, $moduledata ){
        /** @var Module $module */
        $module = $this->getModuleById( $user, $id );
        /** @var Module $module */
        $module->setWidth( $moduledata['width']);
        $module->setHeight( $moduledata['height']);
        $module->setXPosition( $moduledata['xPosition']);
        $module->setYPosition( $moduledata['yPosition']);
        switch ($module->getType()){
            case "calendar" :
                /** @var CalendarModule $module */
                $module->setCalendarIds( $moduledata['calendarIds']);
                break;
            case "weather" :
                /** @var WeatherModule $module */
                $module->setLocationId( $moduledata['locationId']);
                break;
        }
        $this->documentManager->flush();
        return $module;
    }

    public function setModules( array $modules, User $user ) {
        $user->setModules($modules);
        $this->documentManager->flush();
    }
}