<?php
namespace Bundles\Shift1\CoreBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;

class ConsoleRouterLocator extends RouterLocator {

    protected function getRouteFilePath() {
        return 'Application/Config/cli-routes.yml';
    }


}