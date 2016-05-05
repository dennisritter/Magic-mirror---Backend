<?php

namespace Perna\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Token for a Google Auth State
 *
 * @ODM\Document(
 *   db="perna",
 *   collection="googleAuthStateTokens"
 * )
 *
 * @author      Jannik Portz
 * @package     Perna\Document
 */
class GoogleAuthStateToken extends UserToken {}