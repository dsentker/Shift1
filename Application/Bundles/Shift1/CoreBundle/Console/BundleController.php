<?php
namespace Bundles\Shift1\CoreBundle\Console;

use Shift1\Core\Console\Command\Controller\CommandController;
use Shift1\Core\Console\Output\Output;
use Shift1\Core\Console\Output\Dialog;
use Shift1\Core\Config\Builder\ConfigBuilder;
use Shift1\Core\Config\Builder\Item\ConfigItem;
use Shift1\Core\Config\Builder\Item\ConfigurableConfigItem;

class BundleController extends CommandController {

    /**
     *
     */
    public function createConfigFromBundlesAction() {
        $converger = $this->get('configConverger');
        /** @var $converger \Shift1\Core\Bundle\Converger\ConfigConverger */
        $builder = new ConfigBuilder();

        $builder->setAddItemPreCallback($this->getBuilderAddItemCallback());
        #$converger->createConfigFile($builder);

        die(print_r($this->getParams()));
    }

    /**
     * @return \Closure
     */
    protected function getBuilderAddItemCallback()  {

        return function(ConfigItem &$item, $path)  {

            if($item instanceof ConfigurableConfigItem && $item->getNeedValueInput()) {
                /** @var $item ConfigurableConfigItem */
                $dialog = new Dialog($item->getPrompt());
                $answer = $dialog->ask()->getAnswer();

                if($item->hasValidatorCallback()) {
                    $callback = $item->getValidatorCallback();
                    $isValid = $callback($answer);
                    if(!$isValid)  {
                        #echo new Output($item->getErrorMessage());
                        $item->setPrompt($item->getErrorMessage());
                        return false;
                    }
                }

                $item->setValue($answer);
            }

            // Everything is fine.
            return true;

        };
    }

}