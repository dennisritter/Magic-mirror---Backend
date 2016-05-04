<?php

namespace Perna\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Document for a Google Access Token
 *
 * @ODM\EmbeddedDocument
 *
 * @author      Jannik Portz
 * @package     Perna\Document
 */
class GoogleAccessToken {

	/**
	 * @ODM\Field(
	 *   name="accessToken",
	 *   type="string"
	 * )
	 * @var       string
	 */
	protected $accessToken;

	/**
	 * @ODM\Field(
	 *   name="refreshToken",
	 *   type="string"
	 * )
	 * @var       string
	 */
	protected $refreshToken;

	/**
	 * @ODM\Field(
	 *   name="tokenType",
	 *   type="string"
	 * )
	 * @var       string
	 */
	protected $tokenType;

	/**
	 * @ODM\Field(
	 *   name="expiresIn",
	 *   type="int"
	 * )
	 * @var       int
	 */
	protected $expiresIn;

	/**
	 * @ODM\Field(
	 *   name="idToken",
	 *   type="string"
	 * )
	 * @var       string
	 */
	protected $idToken;

	/**
	 * @ODM\Field(
	 *   name="created",
	 *   type="timestamp"
	 * )
	 * @var       int
	 */
	protected $created;

	/**
	 * @return string
	 */
	public function getAccessToken() {
		return $this->accessToken;
	}

	/**
	 * @param string $accessToken
	 */
	public function setAccessToken( $accessToken ) {
		$this->accessToken = $accessToken;
	}

	/**
	 * @return string
	 */
	public function getRefreshToken() {
		return $this->refreshToken;
	}

	/**
	 * @param string $refreshToken
	 */
	public function setRefreshToken( $refreshToken ) {
		$this->refreshToken = $refreshToken;
	}

	/**
	 * @return string
	 */
	public function getTokenType() {
		return $this->tokenType;
	}

	/**
	 * @param string $tokenType
	 */
	public function setTokenType( $tokenType ) {
		$this->tokenType = $tokenType;
	}

	/**
	 * @return int
	 */
	public function getExpiresIn() {
		return $this->expiresIn;
	}

	/**
	 * @param int $expiresIn
	 */
	public function setExpiresIn( $expiresIn ) {
		$this->expiresIn = $expiresIn;
	}

	/**
	 * @return string
	 */
	public function getIdToken() {
		return $this->idToken;
	}

	/**
	 * @param string $idToken
	 */
	public function setIdToken( $idToken ) {
		$this->idToken = $idToken;
	}

	/**
	 * @return int
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * @param int $created
	 */
	public function setCreated( $created ) {
		$this->created = $created;
	}

}