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

    public function post($module) {
        $this->assertAccessToken();
        $user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
        $modules = $this->moduleService->getModules( $user );
        $data = $this->validateIncomingData( ModuleInputFilter::class );
        switch ($data["type"]){
            case "calendar" :
                $module = new CalendarModule();
                $this->hydrateObject(CalendarModuleHydrator::class, $module, $data);
        }
        $modules->add($module);
        $user->setModules($modules);
        return $this->createDefaultViewModel( $modules );
    }

    public function get() {
        $this->assertAccessToken();
        $user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
        $modules = $this->moduleService->getModules( $user );
        $data = array();
        foreach ($modules as $module){
            $class = get_class($module);
            switch ($class){
                case 'calendar':
                    array_push( $data, $this->extractObject(CalendarModuleHydrator::class, $module) ); 
                    break;
            }
        }
        return $this->createDefaultViewModel( $data );
    }
}