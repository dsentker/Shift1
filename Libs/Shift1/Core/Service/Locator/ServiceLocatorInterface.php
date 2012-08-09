<?php
namespace Shift1\Core\Service\Locator;

interface ServiceLocatorInterface {

    const SERVICE_LOCATOR_SUFFIX = 'Locator';

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
    function getIsSingleton();

    /**
     * @abstract
     * @return bool
     */
    function hasDependentServices();

    /**
     * @abstract
     * @return array
     */
    function getDependentServices();

    /**
     * @abstract
     * @param string $id
     * @param string $service
     * @return void
     */
    function injectService($id, $service);

    /**
     * @abstract
     * @return void
     */
    function initialize();

    /**
     * @abstract
     * @return string
     */
    function getId();
    
}