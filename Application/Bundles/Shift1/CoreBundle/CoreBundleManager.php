<?php
namespace Bundles\Shift1\CoreBundle;

use Bundles\Shift1\CoreBundle\ServiceLocators as Locator;
use Shift1\Core\Bundle\Manager\BundleManager;
use Shift1\Core\Service\Container\ServiceContainer;
use Shift1\Core\Config\Builder\ConfigTreeBuilder;
use Shift1\Core\Config\Builder\AdjustmentRequest;

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

    public function loadApplicationConfiguration() {

        $builder = new ConfigTreeBuilder();

        $builder->node('routing')
                    ->addItem('appWebRoot', '/shift1/public/index_dev.php/',
                              AdjustmentRequest::create()
                                      ->setPrompt('Please enter the the root path of your public folder, including the correspondating file, e.g. /subfolder/public/index_stage.php/ . Dont forget the trailing slash.')
                                      ->setValidation('#^/(.+)/$#', 'Please enter a valid path for your root path. Don\'t forget the leading and trailing slash.')
                             )
            ;

        return $builder;
    }

    public function loadHttpRoutingConfiguration() {
        $builder = RoutingTreeBuilder();
        $builder->addRoute('^/viewpost/<post><format>', 'shift1:blogDemo:post::view', array(
                                         '@post' => array(
                                                'paramConverter: \Bundles\Shift1\BlogDemoBundle\Routing\ParamConverter\BlogpostConverter'
                                         ),
                                         '@format' => array(
                                                'match' => '\.?([a-z]{3,4})?',
                                                'default' => 'html',
                                         )
                                  ));
    }



}
