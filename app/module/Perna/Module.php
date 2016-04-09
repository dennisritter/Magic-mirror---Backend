<?php

namespace Perna;

use Exception;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

/**
 * Module Class for Perna API Module
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

	}

	/** Returns the config array for Zend Loader */
	public function getAutoloaderConfig () : array {
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				),
			),
		);
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