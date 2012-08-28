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

    use Shift1\Core\FrontController\FrontController;
    use Shift1\Core\Autoloader\Autoloader;
    use Shift1\Core\Service\Container\ServiceContainer;
    use Shift1\Core\Bundle\Converger\ServiceLocatorConverger;

    class Bootstrapper  {

        /**
         * @static
         * @param string $environment
         * @return \Shift1\Core\FrontController\FrontController
         */
        public static function getFrontController($environment) {

            \error_reporting(-1);
            \ini_set('display_errors', 1);

            require_once \realpath('../Libs/vendor/autoload.php');
            require_once \realpath(BASEPATH . '/Libs/Shift1/Core/Autoloader/Autoloader.php');

            $shift1Loader = new Autoloader();
            $shift1Loader->register();

            $serviceContainer = new ServiceContainer();
            $serviceContainer->get('parameter')->environment = $environment;

            $serviceLocatorConverger = ServiceLocatorConverger::factory($environment);
            $serviceLocatorConverger->populateContainer($serviceContainer);

            #$serviceContainer->get('log')->log('Service locator instances created. Booting starts now.');
            $serviceContainer->get('exceptionHandler')->register();

            $fc = new FrontController();
            $fc->setServiceContainer($serviceContainer);

            return $fc;

        }

    }
}