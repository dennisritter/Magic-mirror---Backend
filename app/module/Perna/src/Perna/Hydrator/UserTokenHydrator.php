<?php

namespace Perna\Hydrator;

use Perna\Document\UserToken;
use Zend\Hydrator\HydratorInterface;

/**
 * Hydrator for all kinds of UserToken
 *
 * @author      Jannik Portz
 * @package     Perna\Hydrator
 */
class UserTokenHydrator implements HydratorInterface {

	/** @inheritdoc */
	public function extract ( $object ) : array {
		/** @var UserToken $object */
		return [
			'token' => $object->getToken(),
			'expires' => $object->getExpires(),
			'expirationDate' => $object->getExpirationDate()
		];
	}

	/**
	 * @inheritdoc
	 *
	 * A UserToken cannot be hydrated
	 */
	public function hydrate ( array $data, $object ) {
		return $object;
	}
}