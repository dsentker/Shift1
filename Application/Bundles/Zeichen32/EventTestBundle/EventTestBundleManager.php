<?php
namespace Bundles\Zeichen32\EventTestBundle;

use Bundles\Zeichen32\EventTestBundle\ServiceLocators as Locator;
use Shift1\Core\Bundle\Manager\BundleManager;

class EventTestBundleManager extends BundleManager  {

    public function getServiceLocators() {
        return array(
            new Locator\EventDispatcherLocator(),
        );
    }

}
