<?php
namespace Bundles\Shift1\BlogDemoBundle\Console;

use Shift1\Core\Console\Command\Controller\CommandController;

class TestController extends CommandController {

    public function testAction() {
        echo 'This is a test from command line.';
    }

}
