<?php

namespace Perna\Controller;

use Perna\Hydrator\AbstractModuleHydrator;
use Perna\Hydrator\CalenderModuleHydrator;
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