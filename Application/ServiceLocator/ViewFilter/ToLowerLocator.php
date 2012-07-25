<?php
namespace Application\ServiceLocator\ViewFilter;

use Shift1\Core\Service\Locator\AbstractServiceLocator;

class ToLowerLocator extends AbstractServiceLocator  {

    public function __construct() {
        $this->setClassNamespace('\Shift1\Core\View\Filter\ToLower');
    }

}