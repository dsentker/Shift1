<?php
namespace Shift1\Core\Service\Locator;

class ParameterLocator extends AbstractServiceLocator {

    public static $isSingleton = true;

    public function __construct() {
        $this->setClassNamespace('\Shift1\Core\VariableSet\StaticVariableSet');
    }

}
