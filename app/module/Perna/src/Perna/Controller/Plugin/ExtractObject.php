<?php

namespace Perna\Controller\Plugin;

use Perna\Hydrator\AbstractHydrator;
use Zend\Hydrator\HydratorInterface;
use Zend\Hydrator\HydratorPluginManager;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;


/**
 * Controller Plugin for extracting objects
 *
 * @author      Jannik Portz
 * @package     Perna\Controller\Plugin
 */
class ExtractObject extends AbstractPlugin {

	/**
	 * @var       HydratorPluginManager
	 */
	protected $hydratorPluginManager;

	public function __construct ( HydratorPluginManager $hydratorPluginManager ) {
		$this->hydratorPluginManager = $hydratorPluginManager;
	}

	/**
	 * Retrieves the Hydrator and extracts the data from the object
	 * @param     string            $hydratorName   The name of the hydrator to use
	 * @param     object|object[]   $object         The object whose data to extract or an array of objects
	 * @return    array                             Array containing extracted data
	 */
	public function __invoke ( string $hydratorName, $object ) : array {
		/** @var HydratorInterface $hydrator */
		$hydrator = $this->hydratorPluginManager->get( $hydratorName );

		if ( is_array( $object ) && $hydrator ) {
			if ( !$hydrator instanceof AbstractHydrator )
				throw new \InvalidArgumentException("Hydrator {$hydratorName} is not an instance of AbstractHydrator.");

			/** @var AbstractHydrator $hydrator */
			return $hydrator->extractMany( $object );
		}

		if ( !$hydrator instanceof HydratorInterface )
			throw new \InvalidArgumentException("Hydrator {$hydratorName} does not implement HydratorInterface.");

		return $hydrator->extract( $object );
	}
}