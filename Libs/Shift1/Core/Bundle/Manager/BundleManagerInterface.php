<?php
namespace Shift1\Core\Bundle\Manager;

use Shift1\Core\Service\Container\ServiceContainer;

interface BundleManagerInterface {

    /**
     * @abstract
     * @param ServiceContainer $container
     * @return ServiceContainer
     */
    function loadServiceLocators(ServiceContainer $container);

    /**
     * @abstract
     * @return \Shift1\Core\Config\Builder\ConfigTreeBuilder
     */
    function loadApplicationConfiguration();

    /**
     * @abstract
     * @return \Shift1\Core\Routing\Route\RouteCollection
     */
    function loadHttpRouteCollection();

    /**
     * @abstract
     * @return \Shift1\Core\Routing\Route\RouteCollection
     */
    function loadConsoleRouteCollection();

    /**
     * @abstract
     * @return string
     */
    function getVendor();

    /**
     * @abstract
     * @return string
     */
    function getBundleName();

}
