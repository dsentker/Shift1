<?php
namespace Bundles\Shift1\CoreBundle;

use Bundles\Shift1\CoreBundle\ServiceLocators as Locator;
use Shift1\Core\Bundle\Manager\BundleManager;
use Shift1\Core\Service\Container\ServiceContainer;

class CoreBundleManager extends BundleManager  {


    public function loadServiceLocators(ServiceContainer $container) {

        $locators = array(
            'config'                    => new Locator\ConfigLocator(),
            'consoleInputHandler'       => new Locator\ConsoleInputHandlerLocator(),
            'controllerFactory'         => new Locator\ControllerFactoryLocator(),
            'exceptionHandler'          => new Locator\ExceptionHandlerLocator(),
            'log'                       => new Locator\LogLocator(),
            'paramConverterFactory'     => new Locator\ParamConverterFactoryLocator(),
            'request'                   => new Locator\RequestLocator(),
            'router'                    => new Locator\RouterLocator(),
            'cli-router'                => new Locator\ConsoleRouterLocator(),
            'templateAnnotationReader'  => new Locator\TemplateAnnotationReaderLocator(),
            'variableSet'               => new Locator\VariableSetLocator(),
            'view'                      => new Locator\ViewLocator(),
            'viewRenderer'              => new Locator\ViewRendererLocator(),
            'routingResult'               => new Locator\RoutingResultLocator(),

            'viewFilter.escape'         => new Locator\ViewFilter\EscapeLocator(),
            'viewFilter.toLower'        => new Locator\ViewFilter\ToLowerLocator(),
            'viewFilter.ucFirst'        => new Locator\ViewFilter\UcFirstLocator(),
        );

        foreach($locators as $id => $locator) {
            $container->add($id, $locator);
        }

        return $container;

    }

    public function getConsoleMap() {
        return array(
           /** @todo Controller or simple command map bindings? */
        );
    }

}
