<?php
namespace Shift1\Core\Bundle\Manager;

interface BundleManagerInterface {

    /**
     * @abstract
     * @return array
     */
    function getConfig();

    /**
     * @abstract
     * @return array
     */
    function getServiceLocators();

    /**
     * @abstract
     * @return array
     */
    function getRoutes();

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
