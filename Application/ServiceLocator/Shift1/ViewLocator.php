<?php
namespace Application\ServiceLocator\Shift1;

use Shift1\Core\Service\Locator\AbstractServiceLocator;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;


class ViewLocator extends AbstractServiceLocator {

    public function __construct() {

        $this->setClassNamespace('\Shift1\Core\View\View');
        $this->necessitate(array(
                        'shift1.config',
                        'shift1.variableSet',
                        'shift1.viewRenderer',
                        'shift1.templateAnnotationReader',
                        'shift1.controllerViewReloader',
                   ));

    }

    public function initialize() {

        $this->setConstructorArgs(array(
                       $this->get('shift1.config')->view,
                       $this->get('shift1.variableSet'),
                       $this->get('shift1.viewRenderer'),
                       $this->get('shift1.templateAnnotationReader'),
                       $this->get('shift1.controllerViewReloader'),
                  ));

    }
}