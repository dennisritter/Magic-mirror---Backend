<?php

namespace Perna\Controller\Console;

use Perna\Service\CityImportService;
use Zend\Console\Request as ConsoleRequest;

/**
 * Controller for importing a cities dump to the cities DB collection
 *
 * @author      Jannik Portz
 * @package     Perna\Controller\Console
 */
class ImportCitiesController extends AbstractConsoleActionController {

	const ACTION_IMPORT_CITIES = 'importCities';

	/**
	 * @var       CityImportService
	 */
	protected $importer;

	public function __construct ( CityImportService $cityImportService ) {
		$this->importer = $cityImportService;
	}

	public function importCitiesAction () : string {
		/** @var ConsoleRequest $request */
		$request = $this->getRequest();
		$filePath = $request->getParam('dumpPath');
		$numberCities = $this->importer->importCitiesFromFile( $filePath );
		return "Imported {$numberCities} Cities.\r\n";
	}
}