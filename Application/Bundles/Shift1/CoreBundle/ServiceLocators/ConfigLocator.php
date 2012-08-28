<?php
namespace Bundles\Shift1\CoreBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;

class ConfigLocator extends AbstractServiceLocator {

    public static $isSingleton = true;

    public function __construct() {
        #$this->setClassNamespace('\Shift1\Core\Config\Reader\Reader');
        $this->setClassNamespace('\Shift1\Core\Config\Reader\ConfigReader');
        $this->dependsOn('parameter');
    }

    public function initialize() {
        $configFile = new File\YamlFile(new InternalFilePath('Application/Config/' . $this->getConfigFile()));
        $this->setConstructorArgs(array($configFile->toArray()));
    }

    protected function getConfigFile() {
        $env = $this->getService('parameter')->environment;
        $file = 'app';
        if(!empty($env)) {
            $file .= '_' . $env;
        }
        return $file . '.yml';
    }
}
