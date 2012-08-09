<?php
namespace Bundles\Shift1\CoreBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;

class ControllerViewReloaderLocator extends AbstractServiceLocator {

    public function __construct() {

        $this->setClassNamespace('\Shift1\Core\View\ControllerViewReloader\ControllerViewReloader');
        $this->dependsOn(array( 'shift1.controllerFactory', ));
    }

    public function initialize() {

        $this->setConstructorArgs(array(
                       $this->getService('shift1.controllerFactory'),
                  ));

    }
}