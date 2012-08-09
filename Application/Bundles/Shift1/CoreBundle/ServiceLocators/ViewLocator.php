<?php
namespace Bundles\Shift1\CoreBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;

class ViewLocator extends AbstractServiceLocator {

    public function __construct() {

        $this->setClassNamespace('\Shift1\Core\View\View');
        $this->dependsOn(array(
                        'shift1.config',
                        'shift1.variableSet',
                        'shift1.viewRenderer',
                        'shift1.templateAnnotationReader',
                        'shift1.controllerViewReloader',
                   ));

    }

    public function initialize() {

        $this->setConstructorArgs(array(
                       $this->getService('shift1.config')->view,
                       $this->getService('shift1.variableSet'),
                       $this->getService('shift1.viewRenderer'),
                       $this->getService('shift1.templateAnnotationReader'),
                       $this->getService('shift1.controllerViewReloader'),
                  ));

    }
}