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
     *   description="Adds a new Module to logged in User",
     *   tags={"modules"},
     *   @SWG\Parameter(
     *    in="body",
     *    name="body",
     *    @SWG\Schema(ref="Module", description="The changed Moduledata")
     *   ),
     *   @SWG\Response(
     *    response="200",
     *    description="Updates the selected Module",
     *    @SWG\Schema(ref="Module", description="The changed Module")
     *  )
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
        }
    }

    /**
     * @SWG\Get(
     *   path="/modules/{id}",
     *   summary="Get Module",
     *   description="Serves data for one Module",
     *   operationId="getModule",
     *   tags={"modules"},
     *   @SWG\Response(
     *    response="200",
     *    description="The Module have successfully be retrieved",
     *    @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref="Module")
     *   )
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
            default :
                $data = null;
        }
        return $this->createDefaultViewModel( $data );
    }

    /**
     * @SWG\Delete(
     *   path="/modules/{id}",
     *   summary="Delets Module",
     *   description="Delete one Module",
     *   operationId="deleteModule",
     *   tags={"modules"},
     *   @SWG\Response(
     *    response="201",
     *    description="The Module have successfully be deleted",
     *    @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref="Module")
     *   )
     *  )
     * )
     */
    public function delete( array $params ) {
        $this->assertAccessToken();
        $user = $this->authenticationService->findAuthenticatedUser( $this->accessToken );
        $this->moduleService->removeModule( $user,  $params['id']);
        return $this->get( $params );
    }
}