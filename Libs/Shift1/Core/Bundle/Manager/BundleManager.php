<?php
namespace Shift1\Core\Bundle\Manager;

use Shift1\Core\Service\Container\ServiceContainer;
/**
 * A basic bundle manager that is
 * perfectly valid and returns
 * a minimal configuration
 */
class BundleManager implements BundleManagerInterface {

    public function loadServiceLocators(ServiceContainer $container) {

    }

    public function getApplicationConfig() {

    }

    public function loadRoutes() {

    }

    public function getConsoleMap() {

    }

    public function getEventListener() {

    }

    public function getVendor() {
        $bundleManagerNamespaceParts = \explode('\\', \get_class($this));
        \array_pop($bundleManagerNamespaceParts); // Strip bundle manager name
        \array_pop($bundleManagerNamespaceParts); // Strip bundle name
        return \array_pop($bundleManagerNamespaceParts);
    }

}
