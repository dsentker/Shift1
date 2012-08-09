<?php
namespace Shift1\Core\Bundle\Converger;

class BundleServiceLocatorConverger extends BundleConverger {

    /**
     * @return array
     */
    public function getServiceLocators() {

        $locators = array();

        foreach($this->getBundleManager() as $bundleManager) {

            /** @var $bundleManager \Shift1\Core\Bundle\Manager\BundleManagerInterface */
            $vendor = \lcfirst($bundleManager->getVendor());
            foreach($bundleManager->getServiceLocators() as $serviceLocator) {
                /** @var $serviceLocator \Shift1\Core\Service\Locator\ServiceLocatorInterface */
                $key = $vendor . '.' . \lcfirst($serviceLocator->getId());
                $locators[$key] = $serviceLocator;
            }

        }

        return $locators;

    }



}
