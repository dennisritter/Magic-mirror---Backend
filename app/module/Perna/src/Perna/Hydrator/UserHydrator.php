<?php

namespace Perna\Hydrator;

use Perna\Document\User;
use Zend\Hydrator\HydratorInterface;

class UserHydrator implements HydratorInterface {

	/** @inheritdoc */
	public function extract ( $object ) : array {
		/** @var User $object */
		return [
			'firstName' => $object->getFirstName(),
			'lastName' => $object->getLastName(),
			'email' => $object->getEmail(),
			'lastLogin' => $object->getLastLogin()
		];
	}
	
	/** @inheritdoc */
	public function hydrate ( array $data, $object){
		/** @var User $object */
		$object->setFirstName( $data['firstName'] );
		$object->setLastName( $data['lastName'] );
		$object->setEmail( $data['email'] );
		
		return $object;
	}
}