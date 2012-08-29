<?php
namespace Bundles\Shift1\CoreBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File\YamlFile;
use Shift1\Core\Routing\Route\RouteCollection;

class RouterLocator extends AbstractServiceLocator {

    public static $isSingleton = true;

    public function __construct() {
        $this->dependsOn(array(
            'request',
            'routingResult'
        ));

        $this->setClassNamespace('\Shift1\Core\Routing\Router\Router');
    }

    public function getInstance() {

        $router = $this->getClassNamespace();
        $routeConfig = new YamlFile(new InternalFilePath('Application/Config/routes.yml'));
        $collection = RouteCollection::fromConfig($routeConfig);
        return $router::fromCollection($collection, $this->getService('request'), $this->getService('routingResult'));

    }
}