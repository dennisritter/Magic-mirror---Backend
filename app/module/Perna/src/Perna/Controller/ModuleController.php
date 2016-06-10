<?php

namespace Perna\Controller;

use Perna\Document\Module;
use Perna\Hydrator\CalendarModuleHydrator;
use Perna\Hydrator\TimeModuleHydrator;
use Perna\Hydrator\WeatherModuleHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\ModuleService;
use Swagger\Annotations as SWG;

class ModuleController extends AbstractAuthenticatedApiController {

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
     *   path="/modules/{id}",
     *   summary="Change module",
     *   description="Replaces the module data",
     *   tags={"modules"},
     *   @SWG\Parameter(ref="#/parameters/accessToken"),
     *   @SWG\Parameter(
     *    in="body",
     *    name="body",
     *    @SWG\Schema(ref="Module", description="The changed Module data")
     *   ),
     *   @SWG\Response(
     *    response="200",
     *    description="Updates the selected Module",
     *    @SWG\Schema(
     *      @SWG\Property(property="success", type="boolean", default=true),
     *      @SWG\Property(property="data", ref="Module")
     *    )
     *  ),
     *   @SWG\Response(response="403", ref="#/responses/403"),
     *   @SWG\Response(response="404", ref="#/responses/404"),
     *   @SWG\Response(response="422", ref="#/responses/422")
     * )
     */
    public function put( array $params ) {
        $this->assertAccessToken();
        $user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
        /** @var Module $module */
        $rawdata = json_decode($this->request->getContent(), true);
        $module = $this->moduleService->getModuleById( $user, $params['id']);
        switch ($rawdata['type']){
            case 'calendar' :
                $this->hydrateObject(CalendarModuleHydrator::class, $module, $rawdata);
                $module = $this->moduleService->setModule( $user, $params['id'], $rawdata );
                return $this->createDefaultViewModel($this->extractObject(CalendarModuleHydrator::class, $module));
                break;
            case 'weather' :
                $this->hydrateObject(WeatherModuleHydrator::class, $module, $rawdata);
                $module = $this->moduleService->setModule( $user, $params['id'], $rawdata );
                return $this->createDefaultViewModel($this->extractObject(WeatherModuleHydrator::class, $module));
                break;
            case 'time' :
                $this->hydrateObject(TimeModuleHydrator::class, $module, $rawdata);
                $module = $this->moduleService->setModule( $user, $params['id'], $rawdata );
                return $this->createDefaultViewModel($this->extractObject(TimeModuleHydrator::class, $module));
                break;
            default:
                return $this->get($params);
        }
    }

    /**
     * @SWG\Get(
     *   path="/modules/{id}",
     *   summary="Get Module",
     *   description="Serves data for one Module",
     *   operationId="getModule",
     *   tags={"modules"},
     *   @SWG\Parameter(ref="#/parameters/accessToken"),
     *   @SWG\Response(
     *    response="200",
     *    description="The Module have successfully be retrieved",
     *    @SWG\Schema(
     *      @SWG\Property(property="success", type="boolean", default=true),
     *      @SWG\Property(property="data", ref="Module")
     *   ),
     *   @SWG\Response(response="403", ref="#/responses/403"),
     *   @SWG\Response(response="404", ref="#/responses/404")
     *  )
     * )
     */
    public function get( array $params ) {
        $this->assertAccessToken();
        $user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
        $module = $this->moduleService->getModuleById( $user, $params['id'] );
        /** @var Module $module */
        $type = $module->getType();
        switch ($type){
            case 'calendar':
                $data = $this->extractObject(CalendarModuleHydrator::class, $module);
                break;
            case 'weather':
                $data = $this->extractObject(WeatherModuleHydrator::class, $module);
                break;
            case 'time':
                $data = $this->extractObject(TimeModuleHydrator::class, $module);
                break;
            default :
                $data = null;
        }
        return $this->createDefaultViewModel( $data );
    }

    /**
     * @SWG\Delete(
     *   path="/modules/{id}",
     *   summary="Deletes Module",
     *   description="Delete one Module",
     *   operationId="deleteModule",
     *   tags={"modules"},
     *   @SWG\Response(
     *    response="200",
     *    description="The Module have successfully be deleted",
     *    @SWG\Schema(
     *      @SWG\Property(property="success", type="boolean", default=true)
     *    )
     *  ),
     *   @SWG\Response(response="403", ref="#/responses/403"),
     *   @SWG\Response(response="404", ref="#/responses/404")
     * )
     */
    public function delete( array $params ) {
        $this->assertAccessToken();
        $user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
        $this->moduleService->removeModule( $user,  $params['id']);
        return $this->createDefaultViewModel(['success' => true]);
    }
}