<?php

namespace Perna\Console;

use Perna\Controller\Console\AbstractConsoleActionController;
use Perna\Helper\ConsoleOutput;

abstract class ConsoleActionRunner {

	/**
	 * @var AbstractConsoleActionController
	 */
	protected $controller;

	public function __construct ( AbstractConsoleActionController $controller ) {
		$this->controller = $controller;
	}

	abstract protected function action () : string;
	
	public function run () : string {
		try {
			return $this->action();
		} catch ( ConsoleException $e ) {
			$out = new ConsoleOutput( false );
			$out->addLine( 'Error: ' . $e->getMessage() );
			return $out->getString();
		}
	}
}