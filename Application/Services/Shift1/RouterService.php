<?php
namespace Application\Services\Shift1;

use Shift1\Core\Service\AbstractService;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;



class RouterService extends AbstractService {

    public static $isSingleton = true;

    public function __construct() {
        $this->necessitate(array('shift1.request', 'shift1.paramConverterFactory'));
        $this->setClassNamespace('\Shift1\Core\Router\Router');
    }

    public function getInstance() {

        $classNamespace = $this->getClassNamespace();
        $routes = new File\YamlFile(new InternalFilePath('Application/Config/routes.yml'));
        return $classNamespace::fromConfig($this->get('shift1.request'), $routes, $this->get('shift1.paramConverterFactory'));

    }
}