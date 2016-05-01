<?php

namespace Perna\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Swagger\Annotations as SWG;

/**
 * @ODM\Document(
 *   db="perna",
 *   collection="accessTokens"
 * )
 *
 * @SWG\Definition(
 *   @SWG\Xml(name="AccessToken")
 * )
 */
class AccessToken extends UserToken {

	/**
	 * @ODM\EmbedOne(targetDocument="RefreshToken")
	 * @SWG\Property(property="refreshToken", ref="UserToken")
	 * @var       RefreshToken
	 */
	protected $refreshToken;

	/**
	 * @return RefreshToken
	 */
	public function getRefreshToken () {
		return $this->refreshToken;
	}

	/**
	 * @param RefreshToken $refreshToken
	 */
	public function setRefreshToken ( $refreshToken ) {
		$this->refreshToken = $refreshToken;
	}
}