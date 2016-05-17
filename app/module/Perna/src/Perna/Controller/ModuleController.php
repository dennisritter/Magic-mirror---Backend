<?php

namespace Perna\Controller;

use Perna\Hydrator\AbstractModuleHydrator;
use Perna\Hydrator\CalenderModuleHydrator;
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
        $modules = $this->moduleService->getModules( $user );
        $data = $this->validateIncomingData( ModuleInputFilter::class );
        $module = array();
        switch ($data["type"]){
            case "calender" :
                $this->hydrateObject(CalenderModuleHydrator::class, $module, $data);
        }
        array_push( $modules, $module);
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
                case 'CalenderModule':
                    array_push( $data, $this->extractObject(CalenderModuleHydrator::class, $module) ); 
                    break;
            }
        }
        return $this->createDefaultViewModel( $data );
    }
}