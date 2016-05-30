<?php

namespace Perna\Controller;

use Perna\Document\CalendarModule;
use Perna\Document\Module;
use Perna\Hydrator\CalendarModuleHydrator;
use Perna\InputFilter\ModuleInputFilter;
use Perna\Service\AuthenticationService;
use Perna\Service\ModuleService;
use Swagger\Annotations;
use Zend\Http\Request;

class ModulesController extends AbstractAuthenticatedApiController {

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
        $rawData = json_decode($this->request->getContent(), true);
        $modules = [];
        foreach ( $rawData as $key){
            switch ($key["type"]){
                case "calendar" :
                    $module = new CalendarModule();
                    $this->hydrateObject(CalendarModuleHydrator::class, $module, $key);
                    array_push( $modules, $module);
            }
        }
        $this->moduleService->setModules( $modules, $user );
        return $this->createDefaultViewModel( $modules );
    }

    public function post() {
        $this->assertAccessToken();
        $user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
        $data = $this->validateIncomingData(ModuleInputFilter::class);
        /** @var $rawData string */
        $rawData = json_decode($this->request->getContent(), true);
        $module = null;
        switch ($data["type"]){
            case "calendar" :
                $data['calendarIds'] = $rawData['calendarIds'];
                $module = new CalendarModule();
                $this->hydrateObject(CalendarModuleHydrator::class, $module, $data);
        }
        $this->moduleService->addModule($user, $module);
        return $this->createDefaultViewModel( $this->extractObject( CalendarModuleHydrator::class, $module) );
    }

    public function get() {
        $this->assertAccessToken();
        $user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
        $modules = $this->moduleService->getModules( $user );
        $data = [];
        foreach ($modules as $module){
            /** @var Module $module */
            $type = $module->getType();
            switch ($type){
                case 'calendar':
                    array_push( $data, $this->extractObject(CalendarModuleHydrator::class, $module) );
            }
        }
        return $this->createDefaultViewModel( $data );
    }



    public function delete() {
        $this->assertAccessToken();
        $user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
        /** @var Request $request */
        $request = $this->getRequest();
        $this->moduleService->removeModule( $user,  $request->getQuery("id"));
        return $this->get();
    }
}