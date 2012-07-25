<?php
namespace Application\ServiceLocator\Shift1;

use Shift1\Core\Service\Locator\AbstractServiceLocator;

class VariableSetLocator extends AbstractServiceLocator {

    public function __construct() {
        $this->setClassNamespace('\Shift1\Core\View\VariableSet\VariableSet');
    }

}