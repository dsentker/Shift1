<?php
namespace Shift1\Core\Bundle\Manager;
/**
 * A basic bundle manager that is
 * perfectly valid and returns
 * a minimal configuration
 */
class BundleManager implements BundleManagerInterface {

    public function getConfig() {

    }

    public function getServiceLocators() {

    }

    public function getRoutes() {

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
