<?php

namespace Perna\Hydrator;

use Perna\Document\UserToken;

/**
 * Hydrator for all kinds of UserToken
 *
 * @author      Jannik Portz
 * @package     Perna\Hydrator
 */
class UserTokenHydrator extends AbstractHydrator {

	/** @inheritdoc */
	public function extract ( $object ) : array {
		/** @var UserToken $object */
		$data = [
			'token' => $object->getToken(),
			'expirationDate' => $this->extractDateTime( $object->getExpirationDate() )
		];

		return $data;
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