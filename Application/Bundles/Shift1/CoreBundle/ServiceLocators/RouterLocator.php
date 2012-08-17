<?php
namespace Bundles\Shift1\CoreBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;



class RouterLocator extends AbstractServiceLocator {

    public static $isSingleton = true;

    public function __construct() {
        $this->dependsOn(array(
        //    'request',
        //    'paramConverterFactory'
        ));

        $this->setClassNamespace('\Shift1\Core\Routing\Router\Router');
    }

    public function getInstance() {

        $classNamespace = $this->getClassNamespace();
        $routeConfig = new File\YamlFile(new InternalFilePath('Application/Config/routes.yml'));
        $routes = $routeConfig->toArray();
        return $classNamespace::fromConfig(
          //   $this->getService('request'),
            $routes
        // $this->getService('paramConverterFactory')
        );

    }
}