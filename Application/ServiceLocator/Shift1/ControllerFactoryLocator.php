<?php
namespace Application\ServiceLocator\Shift1;

use Shift1\Core\Service\Locator\AbstractServiceLocator;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;

class ControllerFactoryLocator extends AbstractServiceLocator {

    public function __construct() {

        $this->setClassNamespace('\Shift1\Core\Controller\Factory\ControllerFactory');
        $this->necessitate('shift1.config');
    }

    public function initialize() {

        $this->setConstructorArgs(array(
                       $this->get('shift1.config')->controller,
                  ));

    }

}