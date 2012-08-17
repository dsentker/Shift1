<?php
namespace Bundles\Shift1\CoreBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;

class ViewLocator extends AbstractServiceLocator {

    public function __construct() {

        $this->setClassNamespace('\Shift1\Core\View\View');
        $this->dependsOn(array(
                        'config',
                        'variableSet',
                        'viewRenderer',
                        'templateAnnotationReader',
                        'controllerFactory',
                   ));

    }

    public function initialize() {

        $this->setConstructorArgs(array(
                       $this->getService('config')->view,
                       $this->getService('variableSet'),
                       $this->getService('viewRenderer'),
                       $this->getService('templateAnnotationReader'),
                       $this->getService('controllerFactory'),
                  ));

    }
}