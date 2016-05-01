<?php

namespace Perna\Hydrator;

use Perna\Document\AccessToken;

/**
 * Hydrator for an AccessToken
 *
 * @author      Jannik Portz
 * @package     Perna\Hydrator
 */
class AccessTokenHydrator extends UserTokenHydrator {

	/** @inheritdoc */
	public function extract ( $object ) {
		/** @var AccessToken $object */
		$data = parent::extract( $object );
		$data['refreshToken'] = parent::extract( $object->getRefreshToken() );
		return $data;
	}
}