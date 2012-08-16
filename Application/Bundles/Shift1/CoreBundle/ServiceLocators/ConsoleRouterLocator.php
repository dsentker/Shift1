<?php
namespace Bundles\Shift1\CoreBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;

class ConsoleRouterLocator extends RouterLocator {

    public function getInstance() {

        $classNamespace = $this->getClassNamespace();
        $routes = new File\YamlFile(new InternalFilePath('Application/Config/cli-routes.yml'));
        return $classNamespace::fromConfig($this->getService('request'), $routes, $this->getService('paramConverterFactory'));

    }
}