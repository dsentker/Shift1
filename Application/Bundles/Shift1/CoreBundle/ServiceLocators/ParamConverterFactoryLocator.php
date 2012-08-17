<?php
namespace Bundles\Shift1\CoreBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;

class ParamConverterFactoryLocator extends AbstractServiceLocator {

    public static $isSingleton = true;

    public function __construct() {
        $this->setClassNamespace('\Shift1\Core\Routing\ParamConverter\Factory\ParamConverterFactory');
    }

}