<?php
namespace Application\ServiceLocator\Shift1;

use Shift1\Core\Service\Locator\AbstractServiceLocator;

class ContextLocator extends AbstractServiceLocator {

    public static $isSingleton = true;

    public function __construct() {
        $this->setClassNamespace('\Shift1\Core\Context\Context');
    }
}