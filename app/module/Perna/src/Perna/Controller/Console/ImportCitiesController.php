<?php

namespace Perna\Controller\Console;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Controller for importing a cities dump to the cities DB collection
 *
 * @author      Jannik Portz
 * @package     Perna\Controller\Console
 */
class ImportCitiesController extends AbstractConsoleActionController {

	const ACTION_IMPORT_CITIES = 'importCities';

	/**
	 * @var       DocumentManager
	 */
	protected $documentManager;

	public function __construct ( DocumentManager $documentManager ) {
		$this->documentManager = $documentManager;
	}

	public function importCitiesAction () : string {
		return "Hello World\r\n";
	}
}