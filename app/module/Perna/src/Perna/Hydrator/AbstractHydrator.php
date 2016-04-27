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
	 * @return    string              The string representation of the DateTime according to RFC3339
	 */
	protected function extractDateTime ( DateTime $dateTime ) : string {
		return $dateTime->format( DateTime::RFC3339 );
	}
}