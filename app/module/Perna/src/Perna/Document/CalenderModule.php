<?php
/**
 * Created by PhpStorm.
 * User: Hannes
 * Date: 16.05.2016
 * Time: 16:34
 */

namespace Perna\Document;


/**
 * Document-Class for CalenderModules
 *
 * @ODM\EmbeddedDocument
 *
 * @author      Johannes Knauft
 * @package     Perna\Documents
 */
class CalenderModule extends Module {

    /**
     * @ODM\Field(
     *     name = "calenderIds",
     *     type = "collection"
     * )
     * @var string[]
     */
    protected $calenderIds;
    
    protected function addSetting( $keyValueArray ) {
        $this->settings->push( $keyValueArray );
    }

    protected function removeSetting( $key ) {
        $this->settings->remove( $key );
    }

    protected function setSetting( $key, $value ) {
        $this->settings->set( $key, $value );
    }
}