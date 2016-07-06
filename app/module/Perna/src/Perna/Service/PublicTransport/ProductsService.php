<?php

namespace Perna\Service\PublicTransport;

/**
 * Service dealing with VBB product classes
 *
 * @author      Jannik Portz
 * @package     Perna\Service\PublicTransport
 */
class ProductsService {

	// Regional Trains
	const RE = 'RE';

	// S-Bahn
	const S = 'S';

	// Bus
	const B = 'B';

	// U-Bahn / Underground
	const U = 'U';

	// Tram
	const T = 'T';

	// Ferry
	const F = 'F';

	/**
	 * Array mapping HAFAS Zugart IDs to internal Product code with:
	 *  key:      int w/ position in the bitmap, starting at 0
	 *  value:    string w/ product key
	 *
	 * @var       array
	 */
	const PRODUCT_MAP = [
		0 => self::S,
		1 => self::U,
		2 => self::T,
		3 => self::B,
		4 => self::F,
		6 => self::RE
	];

	/**
	 * Converts a products bitmap to an array of product identifiers
	 * @param     int       $bitmap   The products bitmap as number
	 * @return    string[]            Array containing the product identifiers
	 */
	public function bitmapToProducts ( int $bitmap ) : array {
		$products = [];
		$binDigits = strlen( decbin( $bitmap ) );
		for ( $i = 0; $i < $binDigits; ++$i ) {
			$val = $bitmap & 1;
			if ( $val === 1 && array_key_exists( $i, self::PRODUCT_MAP ) )
				$products[] = self::PRODUCT_MAP[$i];
			$bitmap = $bitmap >> 1;
		}

		return $products;
	}

	/**
	 * Converts an array of product identifiers to a bitmap
	 * @param     string[]  $products The product identifiers
	 * @return    int                 The bitmap as number
	 */
	public function productsToBitmap ( array $products ) : int {
		$bitmap = 0;
		foreach ( $products as $p ) {
			$idx = array_search( $p, self::PRODUCT_MAP );
			if ( $idx > -1 )
				$bitmap += pow(2, $idx);
		}
		return $bitmap;
	}

	/**
	 * Parses a product class available in the product map from an API response
	 * @param     string    $input    The input string (CatOutL)
	 * @return    string              The product identifier
	 *
	 * @throws    \InvalidArgumentException If the input could not be parsed to a valid product class
	 */
	public function parseProduct ( string $input ) : string {
		$input = trim( $input );

		$map = [
			'Bus' => self::B,
			'Tram' => self::T
		];

		if ( array_key_exists( $input, $map ) )
			return $map[ $input ];

		if ( !in_array( $input, self::PRODUCT_MAP ) )
			throw new \InvalidArgumentException("The product type '{$input}' is not available.");

		return $input;
	}
}