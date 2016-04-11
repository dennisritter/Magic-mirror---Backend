<?php

namespace Api\Helper;

/**
 * Helper class for Console Output
 *
 * @author      Jannik Portz
 * @package     Perna\Helper
 */
class ConsoleOutput {

	const DEFAULT_LOG_PATH = 'logs/console.log';

	/**
	 * String
	 * the buffer string
	 *
	 * @var         string
	 */
	protected $string;

	/**
	 * Verbose
	 *
	 * @var         bool
	 */
	protected $verbose;

	/**
	 * @var         string
	 */
	protected $logFile;

	public function __construct ( bool $verbose, string $logFile = self::DEFAULT_LOG_PATH ) {
		$this->verbose = $verbose;
		$this->logFile = $logFile;
	}

	/**
	 * Appends a string to buffer
	 *
	 * @param       string    $string   the string to append
	 * @param       bool      $force    force flag
	 */
	public function add ( string $string, bool $force = false ) {
		if ( !$this->verbose and !$force )
			return;

		$this->string .= ' ' . $string;
	}

	/**
	 * Appends line breaks and a string to buffer
	 *
	 * @param       string    $string   the string to append
	 * @param       bool      $force    force flag
	 * @param       int       $breaks   number of breaks to append
	 */
	public function addLine ( string $string, bool $force = false, int $breaks = 1 ) {
		if ( !$this->verbose and !$force )
			return;

		if ( empty( $this->string ) )
			$breaks--;

		$lb = '';
		for ( $i = 0; $i < $breaks; $i++ ) :
			$lb .= "\r\n";
		endfor;

		$this->string .= $lb . $string;
	}

	/**
	 * Adds the Buffer's content to a log file
	 *
	 * @param     string    $class
	 * @param     string    $method
	 */
	public function writeToLog ( string $class, string $method ) {
		$header = sprintf( "\r\n\r\n%s, %s::%s\r\n================================================\r\n%s", date( 'Y-m-d', time() ), $class, $method, $this->getString() );
		file_put_contents( $this->logFile, $header, FILE_APPEND );
	}

	public function getString () : string {
		return $this->string . "\r\n";
	}

	public function __toString () : string {
		return $this->string . "\r\n";
	}
}