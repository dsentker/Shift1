<?php
namespace Shift1\Core\Bundle\Manager;

use Shift1\Core\Service\Container\ServiceContainer;
use Shift1\Core\Config\Builder\ConfigTreeBuilder;

interface BundleManagerInterface {

    /**
     * @abstract
     * @param ServiceContainer $container
     * @return ServiceContainer
     */
    function loadServiceLocators(ServiceContainer $container);

    /**
     * @abstract
     * @return ConfigTreeBuilder
     */
    function loadApplicationConfiguration();


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
