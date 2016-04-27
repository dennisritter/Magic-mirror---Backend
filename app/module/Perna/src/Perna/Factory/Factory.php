<?php

namespace Perna\Factory;

use Zend\Di\ServiceLocator;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Abstraction for a factory
 *
 * @author      Jannik Portz
 * @package     Perna\Factory
 */
class Factory implements FactoryInterface {

	/**
	 * The class name of the service to register
	 * @var       string
	 */
	protected $className;

	/**
	 * Associative array of dependency configs with:
	 *  key:    string w/ dependency name
	 *  value:  string w/ dependency type
	 *
	 * @var       array
	 */
	protected $dependencies;

	public function __construct ( string $className, array $dependencies ) {
		$this->className = $className;
		$this->dependencies = $dependencies;
	}

	/**
	 * Determines the manager for a specific dependency type
	 * @param     string                  $type           The type of dependency
	 * @param     ServiceLocatorInterface $serviceManager The ServiceManager
	 *
	 * @return    ServiceLocatorInterface         The ServiceLocator for the dependency type
	 */
	protected function getManagerForType ( string $type, ServiceLocatorInterface $serviceManager ) {
		switch ( $type ) {
			case DependencyTypes::SERVICE:
				return $serviceManager;

			case DependencyTypes::HYDRATOR:
				return $serviceManager->get('HydratorManager');

			case DependencyTypes::INPUT_FILTER:
				return $serviceManager->get('InputFilterManager');

			default:
				throw new \InvalidArgumentException("{$type} is not a valid dependency type.");
		}
	}

	/** @inheritdoc */
	public function createService ( ServiceLocatorInterface $serviceLocator ) {
		if ( $serviceLocator instanceof AbstractPluginManager )
			$serviceLocator = $serviceLocator->getServiceLocator();

		$deps = [];
		foreach ( $this->dependencies as $name => $type ) {
			$manager = $this->getManagerForType( $type, $serviceLocator );
			$deps[] = $manager->get( $name );
		}

		$reflection = new \ReflectionClass( $this->className );
		$instance = $reflection->newInstanceArgs( $deps );
		return $instance;
	}

	public function __invoke ( ServiceLocatorInterface $serviceLocator ) {
		return $this->createService( $serviceLocator );
	}
}