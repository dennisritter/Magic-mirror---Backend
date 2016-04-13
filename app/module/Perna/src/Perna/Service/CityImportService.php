<?php

namespace Perna\Service;

class CityImportService {

	public function importCitiesFromFile ( $filePath ) {
		if ( !file_exists( $filePath ) )
			throw new \Exception("File {$filePath} does not exist");

		$handle = fopen( $filePath, 'r' );
		if ( $handle === false )
			throw new \Exception("Could not read file {$filePath}.");

		while ( !feof( $handle ) ) {
			$line = fgets( $handle );
			$data = json_decode( $line, true );
			$this->importCity( $data );
		}
	}

	public function importCity ( array $data ) {

	}
}