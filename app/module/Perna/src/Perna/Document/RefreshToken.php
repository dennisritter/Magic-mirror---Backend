<?php

namespace Perna\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Refresh Token
 *
 * @ODM\EmbeddedDocument
 *
 * @author      Jannik Portz
 * @package     Perna\Document
 */
class RefreshToken extends UserToken {}