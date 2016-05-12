<?php

namespace Perna\Hydrator;

use Zend\Hydrator\HydratorInterface;
use DateTime;

/**
 * Abstraction for a Hydrator
 *
 * @author      Jannik Portz
 * @package     Perna\Hydrator
 */
abstract class AbstractHydrator implements HydratorInterface {

	/**
	 * Extracts a DateTime object to a date-time string according to RFC3339
	 * @param     DateTime  $dateTime The DateTime to extract
	 * @return    string|null         The string representation of the DateTime according to RFC3339
	 *                                or null if $dateTime is not an instance of DateTime
	 */
	protected function extractDateTime ( $dateTime ) {
		if ( !$dateTime instanceof DateTime )
			return null;

		return $dateTime->format( DateTime::RFC3339 );
	}

	/**
	 * Hydrates many objects of the specified class
	 * @param     array     $data     Sequential array of data arrays
	 * @param     string    $class    Name of the class of which to create objects
	 * @return    object[]            The new hydrated objects
	 */
	public function hydrateMany ( array $data, string $class ) : array {
		if ( !class_exists( $class ) )
			throw new \InvalidArgumentException("{$class} is not a valid class.");

		$objects = [];
		foreach ( $data as $item ) {
			$objects[] = $this->hydrate( $item, new $class );
		}
		return $objects;
	}

	/**
	 * Extracts many objects
	 * @param     object[]  $objects  The objects to extract
	 * @return    array               Sequential array of data arrays
	 */
	public function extractMany ( array $objects ) : array {
		$data = [];
		foreach ( $objects as $object ) {
			$data[] = $this->extract( $object );
		}
		return $data;
	}
}