<?php
namespace Shift1\Core\Service;

interface ServiceInterface {

    /**
     * @abstract
     * @return object
     */
    function getInstance();

    /**
     * @static
     * @abstract
     * @return void
     */
    static function getIsSingleton();

    /**
     * @abstract
     * @return void
     */
    function hasNecessitatesServices();

    /**
     * @abstract
     * @param string $id
     * @param string $service
     * @return void
     */
    function inject($id, $service);
    
}