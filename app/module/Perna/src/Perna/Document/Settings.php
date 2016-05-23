<?php

namespace Perna\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument(
 *   db="perna",
 *   collection="settings"
 * )
 *
 * @SWG\Definition(
 *   @SWG\Xml(name="Settings"),
 *   required={""}
 * )
 */
class Settings
{
    /**
     * @ODM\Field(
     *   name="isGoogleAuthenticated",
     *   type="bool"
     * )
     *
     * @var bool
     **/
    protected $isGoogleAuthenticated;

    /**
     * @return boolean
     */
    public function isIsGoogleAuthenticated()
    {
        return $this->isGoogleAuthenticated;
    }

    /**
     * @param boolean $isGoogleAuthenticated
     */
    public function setIsGoogleAuthenticated($isGoogleAuthenticated)
    {
        $this->isGoogleAuthenticated = $isGoogleAuthenticated;
    }
}