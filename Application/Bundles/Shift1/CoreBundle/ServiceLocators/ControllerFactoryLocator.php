<?php
namespace Bundles\Shift1\CoreBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;

class ControllerFactoryLocator extends AbstractServiceLocator {

    public function __construct() {

        $this->setClassNamespace('\Shift1\Core\Controller\Factory\ControllerFactory');
    }

}