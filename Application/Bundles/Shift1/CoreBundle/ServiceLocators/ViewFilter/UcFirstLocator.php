<?php
namespace Bundles\Shift1\CoreBundle\ServiceLocators\ViewFilter;

use Shift1\Core\Service\Locator\AbstractServiceLocator;

class UcFirstLocator extends AbstractServiceLocator  {

    public function __construct() {
        $this->setClassNamespace('\Shift1\Core\View\Filter\UcFirst');
    }

}