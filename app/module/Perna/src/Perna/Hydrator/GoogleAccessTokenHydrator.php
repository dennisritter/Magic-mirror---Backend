<?php

namespace Perna\Hydrator;
use Perna\Document\GoogleAccessToken;

/**
 * Hydrator for a GoogleAccessToken
 *
 * @author      Jannik Portz
 * @package     Perna\Hydrator
 */
class GoogleAccessTokenHydrator extends AbstractHydrator {

	/** @inheritdoc */
	public function extract( $object ) {
		/** @var GoogleAccessToken $object */
		return [
			'access_token' => $object->getAccessToken(),
			'refresh_token' => $object->getRefreshToken(),
			'token_type' => $object->getTokenType(),
			'expires_in' => $object->getExpiresIn(),
			'id_token' => $object->getIdToken(),
			'created' => $object->getCreated()
		];
	}

	/** @inheritdoc */
	public function hydrate( array $data, $object ) {
		/** @var GoogleAccessToken $object */
		$object->setAccessToken( $data['access_token'] );
		$object->setRefreshToken( $data['refresh_token'] );
		$object->setTokenType( $data['token_type'] );
		$object->setExpiresIn( $data['expires_in'] );
		$object->setIdToken( $data['id_token'] );
		$object->setCreated( $data['created'] );
	}

	/**
	 * Extracts a GoogleAccessToken object to a JSON string
	 * @param     GoogleAccessToken   $object   The access token to be extracted
	 * @return    string                        The JSON string containing the access token data
	 */
	public function extractToJson ( GoogleAccessToken $object ) : string {
		return json_encode( $this->extract( $object ) );
	}

	/**
	 * Hydrates a GoogleAccessToken from a JSON string
	 * @param     string              $data     JSON string containing the data
	 * @param     GoogleAccessToken   $object   The access token in which to hydrate the data
	 * @return    GoogleAccessToken             The hydrated GoogleAccessToken
	 */
	public function hydrateFromJson ( string $data, GoogleAccessToken $object ) : GoogleAccessToken {
		return $this->hydrate( json_decode( $data, true ), $object );
	}
}