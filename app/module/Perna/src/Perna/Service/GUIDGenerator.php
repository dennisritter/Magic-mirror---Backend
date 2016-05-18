<?php

namespace Perna\Service;

/**
 * Generator for a GUID
 *
 * @author      http://guid.us/GUID/PHP
 * @package     Perna\Service
 */
class GUIDGenerator {

	/** String length of a hyphened GUID without braces or parenthesis */
	const GUID_LENGTH = 36;

	/**
	 * Generates a random GUID without curly braces
	 * @return    string    The generated GUID
	 */
	public function generateGUID () : string {
		mt_srand((double)microtime()*10000);
		$charId = strtoupper(md5(uniqid(rand(), true)));
		$hyphen = chr(45);
		$uuid = substr($charId, 0, 8).$hyphen
		        .substr($charId, 8, 4).$hyphen
		        .substr($charId,12, 4).$hyphen
		        .substr($charId,16, 4).$hyphen
		        .substr($charId,20,12);
		return $uuid;
	}
}