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
        #$this->dependsOn('parameter');
    }

    public function initialize() {
        $configFile = new File\IniFile(new InternalFilePath('Application/Config/app.ini'), true);
        #$environment = $this->getService('parameter')->environment;
        #$this->setConstructorArgs(array($configFile, $environment));
        $this->setConstructorArgs(array($configFile->toArray()));
    }
}
