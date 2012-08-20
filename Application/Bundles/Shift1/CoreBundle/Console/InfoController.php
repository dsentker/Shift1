<?php
namespace Bundles\Shift1\CoreBundle\Console;

use Shift1\Core\Console\Command\Controller\CommandController;

class InfoController extends CommandController {

    public function test2Action() {
        echo 'This is a test 2 from command line.';
    }

}
