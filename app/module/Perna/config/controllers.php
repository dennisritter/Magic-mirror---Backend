<?php

use Perna\Controller\Console\ImportCitiesController;
use Perna\Controller\RegisterController;
use Perna\Controller\LoginController;
use Perna\Controller\LogoutController;
use Perna\Controller\UserController;
use Perna\Hydrator\UserHydrator;
use Perna\Service\AuthenticationService;
use Perna\Service\CityImportService;
use Perna\Service\UserService;
use Zend\Mvc\Controller\ControllerManager;

return [
	'factories' => [
		ImportCitiesController::class => function ( ControllerManager $controllerManager ) : ImportCitiesController {
			/** @var CityImportService $importer */
			$importer = $controllerManager->getServiceLocator()->get( CityImportService::class );
			return new ImportCitiesController( $importer );
		},
		RegisterController::class => function( ControllerManager $controllerManager ) : RegisterController {
			/**
			 * @var UserService $us
			 * @var UserHydrator $hy
			 */
			$sm = $controllerManager->getServiceLocator();
			$us = $sm->get( UserService::class );
			$hy = $sm->get( 'HydratorManager' )->get( UserHydrator::class );
			return new RegisterController( $us, $hy );
		},
		UserController::class => function( ControllerManager $controllerManager ) : UserController {
			/**
			 * @var UserService $us
			 * @var UserHydrator $hy
			 */
			$sm = $controllerManager->getServiceLocator();
			$us = $sm->get( UserService::class );
			$hy = $sm->get( 'HydratorManager' )->get( UserHydrator::class );
			return new UserController( $us, $hy );
		},
		LoginController::class => function ( ControllerManager $controllerManager ) : LoginController {
			/**
			 * @var AuthenticationService $as
			 */
			$as = $controllerManager->getServiceLocator()->get( AuthenticationService::class );
			return new LoginController( $as );
		},
		LogoutController::class => function ( ControllerManager $controllerManager ) : LogoutController {
			/**
			 * @var AuthenticationService $as
			 */
			$as = $controllerManager->getServiceLocator()->get( AuthenticationService::class );
			return new LogoutController( $as );
		}
	]
];