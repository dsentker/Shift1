<?php
namespace Application\ServiceLocator\Shift1;

use Shift1\Core\Service\Locator\AbstractServiceLocator;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;

class ParamConverterFactoryLocator extends AbstractServiceLocator {

    public static $isSingleton = true;

    public function __construct() {
        $this->setClassNamespace('\Shift1\Core\Router\ParamConverter\Factory\ParamConverterFactory');
    }

}