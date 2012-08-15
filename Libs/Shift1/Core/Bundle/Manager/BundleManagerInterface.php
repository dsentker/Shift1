<?php
namespace Shift1\Core\Bundle\Manager;

use Shift1\Core\Service\Container\ServiceContainer;

interface BundleManagerInterface {

    /**
     * @abstract
     * @return array
     */
    function getApplicationConfig();

    /**
     * @abstract
     * @return array
     */
    function loadServiceLocators(ServiceContainer $container);

    /**
     * @abstract
     * @return array
     */
    function loadRoutes();

    /**
     * @abstract
     * @return array
     */
    function getConsoleMap();

    /**
     * @abstract
     * @return array
     */
    function getEventListener();

    /**
     * @abstract
     * @return string
     */
    function getVendor();

}
