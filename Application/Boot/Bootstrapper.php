<?php
/**
 *
 *	SHIFT1 Framework
 *	(C) 2011 by Daniel Sentker
 *	Version: 1.0 (pre-alpha)
 *
 *	Licensed under MIT License
 *  see license.txt for further information
 *
 *  @package SHIFT1 Framework
 *  @author Daniel Sentker
 *
 */
namespace Application\Boot;

use Shift1\Core\Config\Manager\Manager as ConfigManager;
use Shift1\Core\FrontController;
use Shift1\Core\Router;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Autoloader\Autoloader;
use Shift1\Core\Config\File;
use Shift1\Core\Service\ServiceContainer;
use Shift1\Core\Request\Request;
use Shift1\Core\Debug;

class Bootstrapper  {

    /**
     * @static
     * @param $environment
     * @return \Shift1\Core\FrontController
     */
    protected static function init($environment) {

        define('BASEPATH', \realpath(__DIR__ . '/../../'));

        require \realpath(BASEPATH . '/Libs/Shift1/Core/Autoloader/Autoloader.php');

        $shift1Loader = new Autoloader();
        $shift1Loader->register();

        Debug\HTMLResponseExceptionHandler::register();

        /** @var $fc \Shift1\Core\FrontController */
        $fc = FrontController::getInstance();

        $fc->setServiceContainer(new ServiceContainer());

        $configFile = new File\IniFile(new InternalFilePath('Application/Config/AppConfig.ini'), true);
        $configManager = new ConfigManager($configFile, $environment);
        $fc->setConfig($configManager);

        $routes = new File\YamlFile(new InternalFilePath('Application/Config/routes.yml'));
        $router = Router\Router::fromConfig($routes);
        $fc->setRouter($router);
        
        return $fc;

    }

    /**
     * Predfined runtime method in
     * development environment
     * @static
     * @return void
     */
    public static function runDev() {

        \error_reporting(-1);
        \ini_set('display_errors', 1);

        $fc = self::init('development');
        $fc->getServiceContainer()->get('Log')->registerErrorHandler(false);
        self::execute($fc);
    }

    /**
     * Predfined runtime method in
     * staging environment
     * @static
     * @return void
     */
    public static function runStaging() {
        \error_reporting(-1);
        $fc = self::init('staging');
        $fc->getServiceContainer()->get('Log')->registerErrorHandler(false);
        self::execute($fc);

    }

    /**
     * Predfined runtime method in
     * production environment
     * @static
     * @return void
     */
    public static function runProd() {

        \error_reporting(0);
        \ini_set('display_errors', 0);

        /** @var $fc \Shift1\Core\FrontController\FrontController */
        $fc = self::init('production');

        // Override the non-silent exception handler
        Debug\SilentExceptionHandler::register();
        self::execute($fc);
    }


    /**
     * @static
     * @param \Shift1\Core\FrontController $fc
     * @return mixed
     */
    protected static function execute(FrontController $fc) {
        $request = Request::fromGlobals();
        $fc->handle($request);
    }

}