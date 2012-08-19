<?php
namespace Bundles\Shift1\CoreBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;

class ConsoleRouterLocator extends RouterLocator {

    public function getInstance() {

        $classNamespace = $this->getClassNamespace();
        $routeConfig = new File\YamlFile(new InternalFilePath('Application/Config/cli-routes.yml'));
        $routes = $routeConfig->toArray();
        return $classNamespace::fromConfig($routes, $this->getService('request'), $this->getService('routingResult'));

    }
}