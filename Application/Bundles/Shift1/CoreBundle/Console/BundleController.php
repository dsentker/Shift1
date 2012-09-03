<?php
namespace Bundles\Shift1\CoreBundle\Console;

use Shift1\Core\Console\Command\Controller\CommandController;
use Shift1\Core\Config\Builder\ConfigTreeBuilder;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File\Writer\YamlFileWriter;
use Shift1\Core\Console\Output\Output;
use Shift1\Core\Console\Output\Dialog;
use Shift1\Core\Config\Builder\AdjustmentRequest;
use Shift1\Core\Bundle\Converger\RouteConvergerInterface;

class BundleController extends CommandController {

    const ROUTEMODE_HTTP = 'http';
    const ROUTEMODE_CLI  = 'cli';

    /**
     * @param string $ext The file extension without leading dot
     * @param string $env the environment
     * @return \Shift1\Core\Console\Output\Output
     */
    public function createConfigFromBundlesAction($ext = 'yml', $env = '') {

        /** @var $converger \Shift1\Core\Bundle\Converger\ConfigConverger */
        $converger = $this->get('configConverger');
        $converger->setRequestAdjustmentHandler($this->getAdjustmentRequestHandler());

        $env = !empty($env) ? '_' . $this->getParam('env') : null;
        $filename = \sprintf('Application/Config/app%s.%s', $env, $ext);
        $path = new InternalFilePath($filename);

        print new Output(\sprintf('Trying to create file %s...', $path->getAbsolutePath()));

        $builder = new ConfigTreeBuilder();
        $bundleConfigs = $converger->getBundleApplicationConfiguration($builder);
        $writer = new YamlFileWriter();
        $writer->setPath($path->getAbsolutePath());

        $config = $bundleConfigs->getConfig();

        if($writer->write($config)) {
            return new Output(\sprintf('%s successfully created (%d root nodes created)', $path->getPath(), \count($config)));
        } else {
            return new Output(\sprintf('Error: Could not write to %s', $path->getPath()));
        }

    }

    public function createRoutesAction($mode = self::ROUTEMODE_HTTP) {
        /** @var $converger \Shift1\Core\Bundle\Converger\RouteConverger */
        $converger = $this->get('routeConverger');

        switch($mode) {
            case self::ROUTEMODE_HTTP:
                $routes = $converger->getBundleRouteCollection(RouteConvergerInterface::ROUTES_HTTP);
                $filename = 'Application/Config/routes__.yml'; // underscores are just for test purpose
                break;
            case self::ROUTEMODE_CLI:
                $routes = $converger->getBundleRouteCollection(RouteConvergerInterface::ROUTES_CLI);
                $filename = 'Application/Config/cli-routes__.yml'; // underscores are just for test purpose
                break;
            case '':
                return new Output('No route mode found. Try --mode http or --mode cli.');
            default:
                return new Output('Could not find a handler for mode ' . $mode);
        }

        $routesArray = $routes->getVars();
        $path = new InternalFilePath($filename);
        print new Output(\sprintf('Trying to create file %s...', $path->getAbsolutePath()));

        $writer = new YamlFileWriter();
        $writer->setPath($path);

        if($writer->write($routesArray)) {
            return new Output(\sprintf('%s successfully created (%d root nodes created)', $path->getPath(), \count($routesArray)));
        } else {
            return new Output(\sprintf('Error: Could not write to %s', $path->getPath()));
        }





    }

    protected function getAdjustmentRequestHandler() {

        return function(AdjustmentRequest $adjustment, ConfigTreeBuilder $builder, $iterationCount) {

            $dialogText = ($iterationCount === 1) ? $adjustment->getPrompt() : $adjustment->getValidationFailedMessage();
            $dialog = new Dialog($dialogText);
            $input = $dialog->ask()->getAnswer();

            if(empty($input) && $adjustment->hasDefault()) {
                $input = $adjustment->getDefault();
                print new Output(\sprintf('Default value "%s" used for %s ...', $adjustment->getDefault(), $adjustment->getSubject()));
            } elseif(null !== ($callback = $adjustment->getValidatorCallback())) {
                $validatorResult = $callback($input);
                if(false === $validatorResult) {
                    return false;
                }
            }

            $builder->updateItem($adjustment->getSubject(), $input);
            return true;

        };

    }



}