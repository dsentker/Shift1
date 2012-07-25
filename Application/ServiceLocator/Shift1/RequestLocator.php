<?php
namespace Application\ServiceLocator\Shift1;

use Shift1\Core\Service\Locator\AbstractServiceLocator;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;


class RequestLocator extends AbstractServiceLocator {

    public static $isSingleton = true;

    public function __construct() {

        $this->setClassNamespace('\Shift1\Core\Request\Request');
        $this->necessitate('shift1.config');
    }

    public function getInstance() {

        $classNamespace = $this->getClassNamespace();
        return $classNamespace::fromGlobals($this->get('shift1.config')->route->appWebRoot);

    }

}