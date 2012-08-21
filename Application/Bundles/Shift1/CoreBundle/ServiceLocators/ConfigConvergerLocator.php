<?php
namespace Bundles\Shift1\CoreBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;

class ConfigConvergerLocator extends AbstractServiceLocator {

    public function __construct() {

        $this->setClassNamespace('\Shift1\Core\Bundle\Converger\ConfigConverger');
        $this->dependsOn(array('parameter',));
    }

    public function getInstance() {
        $class = $this->getClassNamespace();
        return $class::factory($this->getService('parameter')->environment);
    }
}