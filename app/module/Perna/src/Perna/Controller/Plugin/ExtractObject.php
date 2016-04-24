<?php

namespace Perna\Controller\Plugin;

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
	 * @param     string    $hydratorName   The name of the hydrator to use
	 * @param     object    $object         The object whose data to extract
	 * @return    array                     Array containing extracted data
	 */
	public function __invoke ( string $hydratorName, $object ) : array {
		/** @var HydratorInterface $hydrator */
		$hydrator = $this->hydratorPluginManager->get( $hydratorName );
		return $hydrator->extract( $object );
	}
}