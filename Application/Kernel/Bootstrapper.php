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


namespace Shift1 {
    define('BASEPATH', \realpath(__DIR__ . '/../../'));
}

namespace Application\Kernel {

    use Shift1\Core\Config\Manager\Manager as ConfigManager;
    use Shift1\Core\FrontController;
    use Shift1\Core\Router;
    use Shift1\Core\Autoloader\Autoloader;
    use Shift1\Core\Debug;
    use Shift1\Core\Service\Container\ServiceContainer;
    use Shift1\Core\Bundle\Converger\BundleConverger;

    class Bootstrapper  {

        /**
         * @static
         * @param $environment
         * @return \Shift1\Core\FrontController
         */
        protected static function init($environment) {

            require_once \realpath('../Libs/vendor/autoload.php');
            require \realpath(BASEPATH . '/Libs/Shift1/Core/Autoloader/Autoloader.php');

            $shift1Loader = new Autoloader();
            $shift1Loader->register();

            $serviceContainer = new ServiceContainer();

            $serviceLocatorConverger = BundleConverger::factory($environment);
            $serviceLocatorConverger->convergeServiceLocators($serviceContainer);

            $serviceContainer->get('parameter')->environment = $environment;
            #$serviceContainer->get('exceptionHandler')->register(); // hide me if u got problems

            $log = $serviceContainer->get('log')->log('Service locator instances created.');

            $fc = new FrontController();
            $fc->setServiceContainer($serviceContainer);

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
            $fc->getServiceContainer()->get('log')->registerErrorHandler(false); // hide me if u got problems
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
            $fc->getServiceContainer()->get('log')->registerErrorHandler(false);
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

            /** @var $fc \Shift1\Core\FrontController */
            $fc = self::init('production');

            // Override the non-silent exception handler
            Debug\SilentExceptionHandler::register();
            self::execute($fc);
        }

        /**
         * @static
         * @return \Shift1\Core\FrontController
         */
        public static function runConsole() {
            return self::init('development');
        }


        /**
         * @static
         * @param \Shift1\Core\FrontController $fc
         * @return mixed
         */
        protected static function execute(FrontController $fc) {
            $fc->execute();
        }

    }
}