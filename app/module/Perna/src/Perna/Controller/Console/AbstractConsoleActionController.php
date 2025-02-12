<?php

namespace Perna\Controller\Console;

use Exception;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

/**
 * Abstraction for a Controller for Console Actions
 *
 * @author      Jannik Portz
 * @package     Perna\Controller\Console
 */
abstract class AbstractConsoleActionController extends AbstractActionController {

	/**
	 * Ensure that controller is dispatched from console
	 * @inheritdoc
	 * @throws    Exception If controller has not been dispatched from console
	 */
	public function onDispatch( MvcEvent $e ) {
		$request = $e->getRequest();
		if ( !$request instanceof ConsoleRequest )
			throw new Exception("You may only run this action from a command line");

		return parent::onDispatch( $e );
	}
}