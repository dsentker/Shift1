<?php
namespace Application\ServiceLocator\Shift1;

use Shift1\Core\Service\Locator\AbstractServiceLocator;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;


class ConfigLocator extends AbstractServiceLocator {

    public static $isSingleton = true;

    public function __construct() {
        $this->setClassNamespace('\Shift1\Core\Config\Manager\Manager');
        $this->necessitate('shift1.context');
    }

    public function initialize() {
        $configFile = new File\IniFile(new InternalFilePath('Application/Config/AppConfig.ini'), true);
        $environment = $this->get('shift1.context')->environment;
        $this->setConstructorArgs(array($configFile, $environment));
    }
}
