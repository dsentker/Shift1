<?php
namespace Bundles\Shift1\CoreBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;


class RequestLocator extends AbstractServiceLocator {

    public static $isSingleton = true;

    public function __construct() {

        $this->setClassNamespace('\Shift1\Core\Request\Request');
        $this->dependsOn('config');
    }

    public function getInstance() {

        $classNamespace = $this->getClassNamespace();
        return $classNamespace::fromGlobals($this->getService('config')->get('shift1.core.routing.appWebRoot'));
        #return $classNamespace::fromGlobals($this->getService('config')->route->appWebRoot);

    }

}