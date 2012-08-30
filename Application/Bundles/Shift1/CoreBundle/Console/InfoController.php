<?php
namespace Bundles\Shift1\CoreBundle\Console;

use Shift1\Core\Console\Command\Controller\CommandController;
use Shift1\Core\Console\Output\Output;

class InfoController extends CommandController {

    public function testAction() {
        echo new Output('Hello, here is the Shift1 Framework!');
    }

    public function checkArgsAction() {
        $args = $this->getRequest()->getAppRequest();
        echo new Output('Args for ' . $args . ':');
        foreach($this->getConsoleArgs() as $idx => $arg) {
            if(\is_numeric($idx)) $idx = '#' . $idx;
            echo new Output('Arg ' . $idx .' : ' . \var_export($arg, true));
        }

    }

    public function test2Action() {
        print $this->getParam('f|foo');
    }

}
