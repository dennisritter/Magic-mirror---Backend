<?php

namespace Perna\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document()
 */
class AccessToken extends UserToken {}