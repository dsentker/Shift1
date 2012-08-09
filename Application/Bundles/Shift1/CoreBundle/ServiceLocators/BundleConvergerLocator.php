<?php
namespace Bundles\Shift1\CoreBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;

class BundleConvergerLocator extends AbstractServiceLocator {

    public function __construct() {

        $this->setClassNamespace('\Shift1\Core\Bundle\Converger\BundleConverger');
        $this->dependsOn(array('shift1.config',));
    }

    public function initialize() {

        $this->setConstructorArgs(array(
                       $this->getService('shift1.config')->bundle->namespace,
                  ));

    }
}