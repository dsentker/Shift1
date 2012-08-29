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

    /**
     * @param string $ext The file extension without leading dot
     * @return \Shift1\Core\Console\Output\Output
     */
    public function createConfigFromBundlesAction($ext = 'yml') {

        /** @var $converger \Shift1\Core\Bundle\Converger\ConfigConverger */
        $converger = $this->get('configConverger');
        $converger->setRequestAdjustmentHandler($this->getAdjustmentRequestHandler());

        $env = $this->hasParam('env') ? '_' . $this->getParam('env') : null;
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

    public function createHttpRoutesAction($ext = 'yml') {
        /** @var $converger \Shift1\Core\Bundle\Converger\RouteConverger */
        $converger = $this->get('routeConverger');
        $filename = \sprintf('Application/Config/routes__.%s', $ext);
        $path = new InternalFilePath($filename);
        $writer = new YamlFileWriter();
        $writer->setPath($path);

        print new Output(\sprintf('Trying to create file %s...', $path->getAbsolutePath()));

        $httpRoutes = $converger->getBundleRouteCollection(RouteConvergerInterface::ROUTES_HTTP);
        $httpRoutesArray = $httpRoutes->getVars();

        if($writer->write($httpRoutesArray)) {
            return new Output(\sprintf('%s successfully created (%d root nodes created)', $path->getPath(), \count($httpRoutesArray)));
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