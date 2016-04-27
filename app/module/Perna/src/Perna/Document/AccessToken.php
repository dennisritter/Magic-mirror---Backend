<?php

namespace Perna\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(
 *   db="perna",
 *   collection="accessTokens"
 * )
 */
class AccessToken extends UserToken {}