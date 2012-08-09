<?php
namespace Bundles\Shift1\CoreBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;

class BundleServiceLocatorConvergerLocator extends BundleConvergerLocator {

    public function __construct() {

        parent::__construct();
        $this->setClassNamespace('\Shift1\Core\Bundle\Converger\BundleServiceLocatorConverger');

    }

}