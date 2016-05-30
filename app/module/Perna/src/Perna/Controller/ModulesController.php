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

    /**
     * @SWG\Put(
     *   path="/modules",
     *   summary="Override all modules",
     *   description="Adds a new Module to logged in User",
     *   tags={"modules"},
     *   @SWG\Parameter(
     *    in="body",
     *    name="body",
     *    @SWG\Schema(ref="Module", description="The changed Modulearray")
     *   ),
     *   @SWG\Response(
     *    response="200",
     *    description="Updates the selected Module",
     *    @SWG\Schema(ref="Module", description="The changed Module")
     *  )
     * )
     */
    public function put() {
        $this->assertAccessToken();
        $user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
        /** @var Request $request */
        $request = $this->getRequest();
        /** @var $rawData array */
        $rawData = json_decode( $request->getContent(), true);
        $modules = array();
        foreach ($rawData as $item){
            /** @var Module $module */
            switch ($item['type']){
                case 'calendar' :
                    $module = $this->moduleService->setModule( $user, $item['id'], $item );
                    $this->hydrateObject(CalendarModuleHydrator::class, $module, $item);
                    array_push( $modules, $module);
                    break;
            }
        }
        $this->moduleService->setModules( $modules, $user );
        return $this->get();
    }

    /**
     * @SWG\Post(
     *   path="/modules",
     *   summary="Save new Module",
     *   description="Create a new Module",
     *   tags={"modules"},
     *   @SWG\Parameter(
     *    in="body",
     *    name="body",
     *    @SWG\Schema(ref="Module", description="The new Module")
     *   ),
     *   @SWG\Response(
     *    response="200",
     *    description="Created new Module",
     *    @SWG\Schema(ref="Module", description="The new Module")
     *  )
     * )
     */
    public function post() {
        $this->assertAccessToken();
        $user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
        $data = $this->validateIncomingData(ModuleInputFilter::class);
        /** @var Request $request */
        $request = $this->getRequest();
        /** @var $rawData string */
        $rawData = json_decode($request->getContent(), true);
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

    /**
     * @SWG\Get(
     *   path="/modules",
     *   summary="Get all Modules",
     *   description="Serves data for all Modules",
     *   operationId="getModules",
     *   tags={"modules"},
     *   @SWG\Response(
     *    response="200",
     *    description="The Modules have successfully be retrieved",
     *    @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref="Module")
     *   )
     *  )
     * )
     */
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
}