<?php

error_reporting(-1);
ini_set('display_errors', true);

define('BASEPATH', __DIR__);

require 'Autoload.php';

use Shift1\Core\Config\File as File;
use Shift1\Core\Config\Manager\Manager as ConfigManager;

$fc = \Shift1\Core\FrontController::getInstance();
$configFile = new File\IniFile(BASEPATH . \DIRECTORY_SEPARATOR . 'TestConfig.ini', true);
$configManager = new ConfigManager($configFile, 'development');
$fc->setConfig($configManager);