<?php
namespace Shift1\Core\Bundle\Converger;

use Shift1\Core\Service\Container\ServiceContainer;

class ServiceLocatorConverger extends BundleConverger {

    /**
     * @param \Shift1\Core\Service\Container\ServiceContainer $container
     * @return \Shift1\Core\Service\Container\ServiceContainer
     */
    public function populateContainer(ServiceContainer $container) {

        foreach($this->getBundleManager() as $bundleManager) {
            /** @var $bundleManager \Shift1\Core\Bundle\Manager\BundleManagerInterface */
            $bundleManager->loadServiceLocators($container);
        }

        return $container;

    }

}
