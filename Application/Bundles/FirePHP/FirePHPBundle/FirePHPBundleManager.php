<?php
namespace Bundles\FirePHP\FirePHPBundle;

use Shift1\Core\Bundle\Manager\BundleManager;
use Shift1\Core\Service\Container\ServiceContainer;

class FirePHPBundleManager extends BundleManager {

    public function loadServiceLocators(ServiceContainer $container) {
        $container->add('firePHP', new ServiceLocators\FirePHPLocator());
        $container->add('log', new ServiceLocators\LogLocator()); // extends shift1 log locator
        return $container;
    }

}
