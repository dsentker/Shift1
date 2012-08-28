<?php
namespace Shift1\Core\Bundle\Manager;

use Shift1\Core\Service\Container\ServiceContainer;
use Shift1\Core\Config\Builder\ConfigTreeBuilder;
/**
 * A basic bundle manager that is
 * perfectly valid and returns
 * a minimal configuration
 */
class BundleManager implements BundleManagerInterface {


    /**
     * @param \Shift1\Core\Service\Container\ServiceContainer $container
     * @return ServiceContainer
     */
    public function loadServiceLocators(ServiceContainer $container) {

    }

    /**
     * @return ConfigTreeBuilder
     */
    public function loadApplicationConfiguration() {
        return new ConfigTreeBuilder();
    }

    /**
     * @return string The vendor name
     */
    public function getVendor() {
        $bundleManagerNamespaceParts = \explode('\\', \get_class($this));
        \array_pop($bundleManagerNamespaceParts); // Strip bundle manager name
        \array_pop($bundleManagerNamespaceParts); // Strip bundle name
        return \array_pop($bundleManagerNamespaceParts);
    }

    public function getBundleName() {
        $suffixed = \implode('', \array_slice(explode('\\', \get_class($this)), -1));
        $pos = \strrpos($suffixed, 'Bundle');
        return \substr($suffixed, 0, $pos);
    }

}
