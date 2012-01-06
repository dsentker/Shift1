<?php
namespace Application\Services\Shift1;

use Shift1\Core\Service\AbstractService;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;


class ConfigService extends AbstractService {

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
