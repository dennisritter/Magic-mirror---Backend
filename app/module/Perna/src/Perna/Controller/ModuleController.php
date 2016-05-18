<?php

namespace Perna\Controller;

use Perna\Document\CalendarModule;
use Perna\Document\Module;
use Perna\Hydrator\AbstractModuleHydrator;
use Perna\Hydrator\CalendarModuleHydrator;
use Perna\InputFilter\ModuleInputFilter;
use Perna\Service\AuthenticationService;
use Perna\Service\ModuleService;

class ModuleController extends AbstractAuthenticatedApiController {

    /**
     * @var ModuleService 
     */
    protected $moduleService;

    public function __construct( AuthenticationService $authenticationService, ModuleService $moduleService ) {
        parent::__construct( $authenticationService );
        $this->moduleService = $moduleService;
    }

    public function put() {
        $this->assertAccessToken();
        $user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
        $modules = $this->moduleService->getModules( $user );
        $user->setModules();
    }

    public function post() {
        $this->assertAccessToken();
        $user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
        $data = $this->validateIncomingData( ModuleInputFilter::class );
        $module = null;
//        switch ($data["type"]){
//            case "calendar" :
                $module = new CalendarModule();
                $this->hydrateObject(CalendarModuleHydrator::class, $module, $data);
//        }
        $this->moduleService->addModule($user, $module);
        echo $module;
        return $this->createDefaultViewModel( $this->extractObject( CalendarModuleHydrator::class, $module) );
    }

    public function get() {
        $this->assertAccessToken();
        $user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
        $modules = $this->moduleService->getModules( $user );
        $data = [];
        foreach ($modules as $module){
            /** @var CalendarModule $module */
            $type = $module->getType();
            switch ($type){
                case 'calendar':
                    $temp = new CalendarModule();
                    array_push( $data, $this->extractObject(CalendarModuleHydrator::class, $temp) );
            }
        }
        return $this->createDefaultViewModel( $data );
    }
}