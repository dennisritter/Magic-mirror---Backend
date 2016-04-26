<?php

namespace Perna;

use Exception;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Swagger\Annotations as SWG;

/**
 * Module Class for Perna API Module
 *
 * @SWG\Swagger(
 *    schemes={"http"},
 *    host="api.perna.dev",
 *    basePath="/v1",
 *    consumes={"application/json"},
 *    produces={"application/json"},
 *    @SWG\Info(
 *      version="1.0.0",
 *      title="Perna API",
 *      description="RESTful API for Perna Smart Dashboard"
 *    ),
 *    @SWG\Response(response="400", description="The provided data is invalid"),
 *    @SWG\Response(response="200", description="Success"),
 *    @SWG\Response(response="500", description="An internal server error occurred. No specific information available.")
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
	}

	/** Returns the module config */
	public function getConfig () : array {
		$configMap = [
			'router' => 'router',
			'service_manager' => 'service-manager',
			'controllers' => 'controllers',
			'doctrine' => 'doctrine',
			'console' => 'console',
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