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
     *    path="/modules/{id}",
     *    summary="Update Module with specified id",
     *    operationId="updateModule",
     *      tags={"modules"},
     *    @SWG\Parameter(
     *      name="data",
     *      in="body",
     *      description="The module data as JSON object",
     *      required=true,
     *      @SWG\Schema(
     *        @SWG\Property(property="id", type="string"),
     *        @SWG\Property(property="type", type="string"),
     *        @SWG\Property(property="width", type="int"),
     *        @SWG\Property(property="height", type="int"),
     *        @SWG\Property(property="xPosition", type="string"),
     *        @SWG\Property(property="yPosition", type="string")
     *      )
     *    ),
     *    @SWG\Parameter(
     *        in="header",
     *        name="Access-Token",
     *        type="string",
     *        description="The current access token",
     *        required=true
     *   ),
     *    @SWG\Response(
     *        response="200",
     *        description="Module was successfully updated.",
     *		@SWG\Schema( ref="Module" )
     *      )
     * )
     * @param array $params
     * @throws \ZfrRest\Http\Exception\Client\UnauthorizedException
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
                $module = $this->moduleService->setModule( $user, $params['id'], $module );
                return $this->createDefaultViewModel($this->extractObject(CalendarModuleHydrator::class, $module));
                break;
            default :
                return [];
        }
    }

    /**
     * @SWG\Get(
     *    path="/modules/{}",
     *    summary="Update Module with specified id",
     *    operationId="updateModule",
     *      tags={"modules"},
     *    @SWG\Parameter(
     *      name="data",
     *      in="body",
     *      description="The module data as JSON object",
     *      required=true,
     *      @SWG\Schema(
     *        @SWG\Property(property="id", type="string"),
     *        @SWG\Property(property="type", type="string"),
     *        @SWG\Property(property="width", type="int"),
     *        @SWG\Property(property="height", type="int"),
     *        @SWG\Property(property="xPosition", type="string"),
     *        @SWG\Property(property="yPosition", type="string")
     *      )
     *    ),
     *    @SWG\Parameter(
     *        in="header",
     *        name="Access-Token",
     *        type="string",
     *        description="The current access token",
     *        required=true
     *   ),
     *    @SWG\Response(
     *        response="200",
     *        description="Module was successfully updated.",
     *		@SWG\Schema( ref="Module" )
     *      )
     * )
     * @param array $params
     * @throws \ZfrRest\Http\Exception\Client\UnauthorizedException
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
}