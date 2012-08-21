<?php
namespace Bundles\Shift1\CoreBundle\Console;

use Shift1\Core\Console\Command\Controller\CommandController;
use Shift1\Core\Console\Output\Output;

use Shift1\Core\Config\Builder\ConfigBuilder;

class BundleController extends CommandController {

    public function loadConfigAction() {
        $converger = $this->get('configConverger');
        /** @var $converger \Shift1\Core\Bundle\Converger\ConfigConverger */
        $converger->createConfigFile(new ConfigBuilder());
    }

}