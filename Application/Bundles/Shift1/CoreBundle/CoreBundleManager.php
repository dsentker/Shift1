<?php
namespace Bundles\Shift1\CoreBundle;

use Bundles\Shift1\CoreBundle\ServiceLocators as Locator;
use Shift1\Core\Bundle\Manager\BundleManager;
use Shift1\Core\Service\Container\ServiceContainer;
use Shift1\Core\Config\Builder\ConfigBuilder;
use Shift1\Core\Config\Builder\Item\ConfigItem;
use Shift1\Core\Config\Builder\Item\ConfigurableConfigItem;

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
            'routingResult'             => new Locator\RoutingResultLocator(),
            'configConverger'           => new Locator\ConfigConvergerLocator(),

            'viewFilter.escape'         => new Locator\ViewFilter\EscapeLocator(),
            'viewFilter.toLower'        => new Locator\ViewFilter\ToLowerLocator(),
            'viewFilter.ucFirst'        => new Locator\ViewFilter\UcFirstLocator(),
        );

        foreach($locators as $id => $locator) {
            $container->add($id, $locator);
        }

        return $container;

    }

    public function loadApplicationConfiguration(ConfigBuilder $config) {

        $config->addNode('core')
                    ->addNode('routing')
                        ->addItem(ConfigItem::create('appWebRoot')->setValue('/foo/index_dev.php/'))
                        ->addItem(ConfigItem::create('test1'))
                        ->addItem(ConfigurableConfigItem::create('test2')->needValueInput('Enter the test value:', '#\d{3}#', 'Please enter three digits!'))
                        ->addItem(ConfigurableConfigItem::create('test3')->needValueInput('Enter a word:', function($val) { return $val == 'foo'; }, 'Please enter "foo"!'))
                    ->getNode('core')
                    ->addNode('foo')
                       ->addItem(ConfigItem::create('fooKey')->setValue('fooValue'))
                       ->addItem(ConfigItem::create('fooKey')->setValue('fooValue2'));

        return $config;



    }

}
