<?php
namespace Bundles\Zeichen32\EventTestBundle;

use Bundles\Zeichen32\EventTestBundle\ServiceLocators as Locator;
use Shift1\Core\Bundle\Manager\BundleManager;
use Shift1\Core\Service\Container\ServiceContainer;

class EventTestBundleManager extends BundleManager  {

    public function loadServiceLocators(ServiceContainer $container) {
        $container->add('eventDispatcher', new Locator\EventDispatcherLocator());
        return $container;
    }

}
