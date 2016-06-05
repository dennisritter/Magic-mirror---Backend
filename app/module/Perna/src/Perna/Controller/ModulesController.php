<?php

namespace Perna\Controller;

use Perna\Document\CalendarModule;
use Perna\Document\Module;
use Perna\Document\WeatherModule;
use Perna\Hydrator\CalendarModuleHydrator;
use Perna\Hydrator\WeatherModuleHydrator;
use Perna\InputFilter\ModuleInputFilter;
use Perna\Service\AuthenticationService;
use Perna\Service\ModuleService;
use Swagger\Annotations as SWG;
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
     *   summary="Update Module collection",
     *   description="Replaces the whole Module collection",
     *   tags={"modules"},
     *   @SWG\Parameter(
     *    in="body",
     *    name="body",
     *    @SWG\Schema(ref="Module", description="The changed Module-array")
     *   ),
     *   @SWG\Parameter(ref="#/parameters/accessToken"),
     *   @SWG\Response(
     *    response="200",
     *    description="Updates the selected Module",
     *    @SWG\Schema(
     *      @SWG\Property(property="success", type="boolean", default=true),
     *      @SWG\Property(
     *          property="data",
     *          type="array",
     *          @SWG\Items(ref="Module")
     *      )
     *    )
     *  ),
     *   @SWG\Response(response="403", ref="#/responses/403"),
     *   @SWG\Response(response="422", ref="#/responses/422")
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
                    if( !array_key_exists ( "id", $item ) ){
                        $module = new CalendarModule();
                    }else{
                        $module = $this->moduleService->setModule( $user, $item['id'], $item );
                    }
                    $this->hydrateObject(CalendarModuleHydrator::class, $module, $item);
                    array_push( $modules, $module);
                    break;
                case 'weather' :
                    if( !array_key_exists ( "id", $item ) ){
                        $module = new WeatherModule();
                    }else{
                        $module = $this->moduleService->setModule( $user, $item['id'], $item );
                    }
                    $this->hydrateObject(WeatherModuleHydrator::class, $module, $item);
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
     *   summary="Add new Module",
     *   description="Create a new Module",
     *   tags={"modules"},
     *   @SWG\Parameter(ref="#/parameters/accessToken"),
     *   @SWG\Parameter(
     *    in="body",
     *    name="body",
     *    @SWG\Schema(ref="Module", description="The new Module")
     *   ),
     *   @SWG\Response(
     *    response="200",
     *    description="Created new Module",
     *    @SWG\Schema(
     *      @SWG\Property(property="success", type="boolean", default=true),
     *      @SWG\Property(property="data", ref="Module")
     *    )
     *  ),
     *   @SWG\Response(response="403", ref="#/responses/403"),
     *   @SWG\Response(response="422", ref="#/responses/422")
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
                $this->moduleService->addModule($user, $module);
                return $this->createDefaultViewModel( $this->extractObject( CalendarModuleHydrator::class, $module) );
            case 'weather':
                $module = new WeatherModule();
                $this->hydrateObject(WeatherModuleHydrator::class, $module, $data);
                $this->moduleService->addModule($user, $module);
                return $this->createDefaultViewModel( $this->extractObject( WeatherModuleHydrator::class, $module) );
        }
        return null;
    }

    /**
     * @SWG\Get(
     *   path="/modules",
     *   summary="Get all Modules",
     *   description="Serves data for all Modules",
     *   operationId="getModules",
     *   tags={"modules"},
     *   @SWG\Parameter(ref="#/parameters/accessToken"),
     *   @SWG\Response(
     *    response="200",
     *    description="The Modules have successfully be retrieved",
     *    @SWG\Schema(
     *      @SWG\Property(property="success", type="boolean", default=true),
     *      @SWG\Property(property="data", type="array", @SWG\Items(ref="Module"))
     *   )
     *  ),
     *   @SWG\Response(response="403", ref="#/responses/403"),
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
                    break;
                case 'weather': 
                    array_push( $data, $this->extractObject(WeatherModuleHydrator::class, $module) );
                    break;
            }
        }
        return $this->createDefaultViewModel( $data );
    }
}