<?php
namespace Shift1\Core\Bundle\Manager;

use Shift1\Core\Service\Container\ServiceContainer;
use Shift1\Core\Config\Builder\ConfigBuilder;

interface BundleManagerInterface {

    /**
     * @abstract
     * @param ServiceContainer $container
     * @return array
     */
    function loadServiceLocators(ServiceContainer $container);

    /**
     * @abstract
     * @param ConfigBuilder $config
     * @return ConfigBuilder
     */
    function loadApplicationConfiguration(ConfigBuilder $config);


    /**
     * @abstract
     * @return string
     */
    function getVendor();

}
