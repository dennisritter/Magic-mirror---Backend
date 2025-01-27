<?php

namespace Perna\Test\ApiTest;

use Zend\Loader\AutoloaderFactory;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use RuntimeException;

error_reporting( E_ALL | E_STRICT);
chdir(__DIR__);

/**
 * Bootstrap File for Perna API Blackbox tests
 *
 * @author      http://framework.zend.com/manual/current/en/tutorials/unittesting.html
 * @package     Perna\Test\ApiTest
 */
class Bootstrap {

	/**
	 * The ServiceManager
	 * @var       ServiceManager
	 */
	protected static $serviceManager;

	public static function init () {
		$zf2ModulePaths = array(dirname(dirname(__DIR__)));
		if (($path = static::findParentPath('vendor'))) {
			$zf2ModulePaths[] = $path;
		}
		if (($path = static::findParentPath('module')) !== $zf2ModulePaths[0]) {
			$zf2ModulePaths[] = $path;
		}

		static::initAutoloader();

		// use ModuleManager to load this module and it's dependencies
		$config = [
			'module_listener_options' => [
				'module_paths' => $zf2ModulePaths,
			],
			'modules' => [
				'DoctrineModule',
				'DoctrineMongoODMModule',
				'ZfrRest',
				'ZfrCors',
				'Perna'
			]
		];

		$serviceManager = new ServiceManager( new ServiceManagerConfig() );
		$serviceManager->setAllowOverride( true );
		$serviceManager->setService('ApplicationConfig', $config);
		$serviceManager->get('ModuleManager')->loadModules();
		static::$serviceManager = $serviceManager;
		date_default_timezone_set('UTC');
	}

	public static function chroot () {
		$rootPath = dirname(static::findParentPath('module'));
		chdir($rootPath);
	}

	public static function getServiceManager () {
		return static::$serviceManager;
	}

	protected static function initAutoloader () {
		$vendorPath = static::findParentPath('vendor');

		if (file_exists($vendorPath.'/autoload.php')) {
			include $vendorPath.'/autoload.php';
		}

		if (! class_exists('Zend\Loader\AutoloaderFactory')) {
			throw new RuntimeException(
				'Unable to load ZF2. Run `php composer.phar install`'
			);
		}

		AutoloaderFactory::factory(array(
			'Zend\Loader\StandardAutoloader' => array(
				'autoregister_zf' => true,
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
				),
			),
		));
	}

	protected static function findParentPath($path) {
		$dir = __DIR__;
		$previousDir = '.';
		while (!is_dir($dir . '/' . $path)) {
			$dir = dirname($dir);
			if ($previousDir === $dir) {
				return false;
			}
			$previousDir = $dir;
		}
		return $dir . '/' . $path;
	}
}

Bootstrap::init();
Bootstrap::chroot();