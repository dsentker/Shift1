<?php
namespace Application\ServiceLocator\Shift1;

use Shift1\Core\Service\Locator\AbstractServiceLocator;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;

class ControllerViewReloaderLocator extends AbstractServiceLocator {

    public function __construct() {

        $this->setClassNamespace('\Shift1\Core\View\ControllerViewReloader\ControllerViewReloader');
        $this->necessitate(array(
                        'shift1.controllerFactory',
                   ));
    }

    public function initialize() {

        $this->setConstructorArgs(array(
                       $this->get('shift1.controllerFactory'),
                  ));

    }
}