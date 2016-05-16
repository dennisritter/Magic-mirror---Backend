<?php

namespace Perna\Controller;

use Perna\Service\AuthenticationService;
use Perna\Service\Modules\ModuleService;

class AbstractModuleController extends AbstractAuthenticatedApiController {

    /**
     * @var ModuleService 
     */
    protected $moduleService;

    public function __construct( AuthenticationService $authenticationService, ModuleService $moduleService ) {
        parent::__construct( $authenticationService );
        $this->moduleService = $moduleService;
    }
}