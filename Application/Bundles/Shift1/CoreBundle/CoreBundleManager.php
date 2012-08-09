<?php
namespace Bundles\Shift1\CoreBundle;

use Bundles\Shift1\CoreBundle\ServiceLocators as Locator;
use Shift1\Core\Bundle\Manager\BundleManager;

class CoreBundleManager extends BundleManager  {


    public function getServiceLocators() {
        return array(
            new Locator\ConfigLocator(),
            new Locator\ConsoleInputHandlerLocator(),
            new Locator\ControllerFactoryLocator(),
            new Locator\ControllerViewReloaderLocator(),
            new Locator\ExceptionHandlerLocator(),
            new Locator\LogLocator(),
            new Locator\ParamConverterFactoryLocator(),
            new Locator\RequestLocator(),
            new Locator\RouterLocator(),
            new Locator\TemplateAnnotationReaderLocator(),
            new Locator\VariableSetLocator(),
            new Locator\ViewLocator(),
            new Locator\ViewRendererLocator(),
        );
    }

}
