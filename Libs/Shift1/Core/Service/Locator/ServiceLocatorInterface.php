<?php
namespace Shift1\Core\Service\Locator;

interface ServiceLocatorInterface {

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

    /**
     * @abstract
     * @return void
     */
    function initialize();
    
}