<?php
namespace Application\Services\Shift1;

use Shift1\Core\Service\AbstractService;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;



class ParamConverterFactoryService extends AbstractService {

    public static $isSingleton = true;

    public function __construct() {
        $this->setClassNamespace('\Shift1\Core\Router\ParamConverter\Factory\ParamConverterFactory');
    }

}