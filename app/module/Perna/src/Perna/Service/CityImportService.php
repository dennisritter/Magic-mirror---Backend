<?php

namespace Perna\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Exception;
use Perna\Document\City;
use Zend\Hydrator\HydratorInterface;
use Zend\InputFilter\InputFilter;

/**
 * Service responsible for importing cities from a OpenWeatherMap dump file
 *
 * @author      Jannik Portz
 * @package     Perna\Service
 */
class CityImportService {

	/**
	 * The DocumentManager to use to save the cities
	 * @var       DocumentManager
	 */
	protected $documentManager;

	/**
	 * Hydrator used to hydrate the data to a City object
	 * @var       HydratorInterface
	 */
	protected $hydrator;

	/**
	 * InputFilter used to filter the raw data and check for validity
	 * @var       InputFilter
	 */
	protected $inputFilter;

	public function __construct ( DocumentManager $documentManager, HydratorInterface $hydrator, InputFilter $inputFilter ) {
		$this->documentManager = $documentManager;
		$this->hydrator = $hydrator;
		$this->inputFilter = $inputFilter;
	}

	/**
	 * Imports cities from a OpenWeatherMap dump file.
	 * The specified file must consist of per-line JSON strings containing city data
	 *
	 * @param     string    $filePath Path to the dump file
	 * @return    int                 Number of cities that have been imported
	 * @throws    Exception           If the file could not be found or read
	 */
	public function importCitiesFromFile ( $filePath ) : int {
		if ( !file_exists( $filePath ) )
			throw new Exception("File {$filePath} does not exist");

		$handle = fopen( $filePath, 'r' );
		if ( $handle === false )
			throw new Exception("Could not read file {$filePath}.");

		for ( $i = 0; !feof( $handle ); ++$i ) {
			$line = fgets( $handle );

			if ( $line === false )
				continue;

			$data = json_decode( $line, true );
			$this->importCity( $data );

			if ( $i % 100 === 0 && $i > 0 ) {
				$this->documentManager->flush();
				$this->documentManager->clear();
			}
		}

		$this->documentManager->flush();

		return $i;
	}

	/**
	 * Imports a single city from an associative array
	 * @param     array     $data     Associative array containing city data
	 */
	public function importCity ( array $data ) {
		$this->inputFilter->setData( $data );
		if ( !$this->inputFilter->isValid() )
			return;

		$id = $data['_id'];
		$inDb = $this->documentManager->getRepository( City::class )->find( $id );
		$city = $inDb instanceof City ? $inDb : new City();
		$this->hydrator->hydrate( $data, $city );
		$this->documentManager->persist( $city );
	}
}