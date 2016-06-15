<?php

namespace Perna\Service\PublicTransport;

/**
 * Service dealing with VBB product classes
 *
 * @author      Jannik Portz
 * @package     Perna\Service\PublicTransport
 */
class ProductsService {

	const ICE = 'ICE';
	const IC = 'IC';
	const RE = 'RE';
	const S = 'S';
	const B = 'B';
	const U = 'U';
	const T = 'T';

	/**
	 * Array mapping HAFAS Zugart IDs to internal Product code
	 * The data is currently not correct!
	 * @var       array
	 */
	const PRODUCT_MAP = [
		0 => self::ICE,
		1 => self::IC,
		2 => self::RE,
		4 => self::S,
		5 => self::B,
		7 => self::U,
		8 => self::T
	];

	/**
	 * Converts a products bitmap to an array of product identifiers
	 * @param     int       $bitmap   The products bitmap as number
	 * @return    string[]            Array containing the product identifiers
	 */
	public function bitmapToProducts ( int $bitmap ) : array {
		$products = [];
		$binDigits = ceil( sqrt( $bitmap ) );
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