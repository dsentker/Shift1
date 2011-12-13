<?php
namespace Application\Boot;

use Shift1\Core\Config\Manager\Manager as ConfigManager;
use Shift1\Core\FrontController\FrontController;
use Shift1\Core\Router;
use Shift1\Core\Dispatcher\Dispatcher;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Autoloader\Autoloader;
use Shift1\Core\Config\File;
use Shift1\Core\Service\ServiceContainer;
use Shift1\Core\Request\HttpRequest;
use Shift1\Core\App;

class Bootstrapper  {

    public static function init() {

        require \realpath(BASEPATH . '/Libs/Shift1/Core/Autoloader/Autoloader.php');

        $shift1Loader = new Autoloader();
        $shift1Loader->register();

        $configFilePath = new InternalFilePath('Application/Config/AppConfig.ini');
        $configFile = new File\IniFile($configFilePath, true);

        $configManager = new ConfigManager($configFile, 'development');

        $routes = new File\YamlFile(new InternalFilePath('Application/Config/routes.yml'));
        $router = Router\Router::fromConfig($routes);
        $request = new HttpRequest($router);
        $dispatcher = new Dispatcher($request);
        $frontController = new FrontController($dispatcher);
        $serviceContainer = new ServiceContainer();

        /** @var \Shift1\Core\App $app */
        $app = App::getInstance();
        $app->setFrontController($frontController);
        $app->setConfig($configManager);
        $app->setServiceContainer($serviceContainer);

        self::beforeExecute($app);
        self::runApp($app);

    }

    public static function beforeExecute(App $app) {
        \error_reporting(-1);
        $app->getServiceContainer()->get('Log')->registerErrorHandler(0);
    }


    protected static function runApp(App $app) {
        $app->execute();
    }
}
