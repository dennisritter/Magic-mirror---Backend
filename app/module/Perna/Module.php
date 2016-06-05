<?php

namespace Perna;

use Exception;
use Swagger\Annotations as SWG;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Uri\Uri;
use Zend\Uri\UriFactory;

/**
 * Module Class for Perna API Module
 *
 * @SWG\Swagger(
 *   schemes={"http"},
 *   host="api.perna.dev",
 *   basePath="/v1",
 *   consumes={"application/json"},
 *   produces={"application/json"},
 *   @SWG\Info(
 *    version="1.0.0",
 *    title="Perna API",
 *    description="RESTful API for Perna Smart Dashboard"
 *   ),
 *   @SWG\Response(
 *    response="500",
 *    description="Internal Server Error",
 *    @SWG\Schema(ref="#/definitions/ResponseError")
 *   ),
 *   @SWG\Response(
 *    response="422",
 *    description="The provided parameters or parameter values are invalid.",
 *    @SWG\Schema(
 *      @SWG\Property(property="status_code", type="number", format="int32", description="The HTTP status code", default=422),
 *      @SWG\Property(property="message", type="string", default="Validation Error", description="The main error message"),
 *      @SWG\Property(
 *        property="errors",
 *        allOf={ @SWG\Schema(ref="#/definitions/Errors") }
 *      )
 *    )
 *   ),
 *   @SWG\Response(
 *    response="403",
 *    description="Access-Token is not present in the header or invalid.",
 *    @SWG\Schema(
 *      @SWG\Property(property="status_code", type="number", format="int32", default=403),
 *      @SWG\Property(property="message", type="string")
 *    )
 *   ),
 *   @SWG\Response(
 *    response="404",
 *    description="The specified resource could not be found.",
 *    @SWG\Schema(
 *      @SWG\Property(property="status_code", type="number", format="int32", default=404, description="The HTTP Status Code"),
 *      @SWG\Property(property="message", type="string", description="The error message")
 *    )
 *   ),
 *   @SWG\Parameter(
 *    parameter="accessToken",
 *    name="Access-Token",
 *    in="header",
 *    description="A valid access token for the currently authenticated user",
 *    type="string",
 *    default="A0383A16-A1AF-A68A-DDC2-0DC458340ED4",
 *    required=true
 *   ),
 *   @SWG\Definition(
 *    definition="DayTemperatures",
 *    @SWG\Property(property="average", type="number", format="float", description="The average temperature on that day in Kelvin."),
 *    @SWG\Property(property="min", type="number", format="float", description="The min day temperature on that day in Kelvin."),
 *    @SWG\Property(property="max", type="number", format="float", description="The max day temperature on that day in Kelvin.")
 *   ),
 *   @SWG\Definition(
 *    definition="Errors",
 *    @SWG\Property(
 *      property="[parameterName]",
 *      type="array",
 *      @SWG\Items(type="string")
 *    )
 *  )
 * )
 *
 * @author      Jannik Portz
 * @package     Perna
 */
class Module {

	public function onBootstrap ( MvcEvent $e ) {
		$eventManager = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener->attach($eventManager);

		UriFactory::registerScheme('chrome-extension', Uri::class);
	}

	/** Returns the module config */
	public function getConfig () : array {
		$configMap = [
			'router' => 'router',
			'service_manager' => 'service-manager',
			'controllers' => 'controllers',
			'doctrine' => 'doctrine',
			'hydrators' => 'hydrators',
			'input_filters' => 'input-filters',
			'swagger' => 'swagger',
			'zfr_cors' => 'zfr-cors',
			'view_manager' => 'view-manager',
			'controller_plugins' => 'controller-plugins'
		];

		foreach ( $configMap as &$key ) {
			$key = $this->getConfigFor( $key );
		}

		return $configMap;
	}

	/** Returns the config array for Zend Loader */
	public function getAutoloaderConfig () : array {
		return [
			'Zend\Loader\StandardAutoloader' => [
				'namespaces' => [
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				],
			],
		];
	}

	/**
	 * Returns the config array for a specific config part
	 * @param     string    $key      The config key (file name without extension)
	 * @return    array               Associative config array
	 * @throws    Exception           If config file could not be found
	 */
	protected function getConfigFor ( string $key ) : array {
		$path = sprintf("%s/config/%s.php", __DIR__, $key);

		if ( !file_exists( $path ) )
			throw new Exception( "Partial config {$key} could not be loaded because the expected config file {$key} is not available" );

		$config = (array) include $path;
		return $config;
	}
}