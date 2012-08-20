<?php
namespace Bundles\Shift1\BlogDemoBundle\Console;

use Shift1\Core\Console\Command\Controller\CommandController;
use Shift1\Core\Console\Output;

class TestController extends CommandController {

    public function testAction() {
        echo 'This is a test from command line.';
    }

    public function qAction() {
        $dialog = new Output\Dialog('What is your name?');
        $name = $dialog->ask()->getAnswer();
        return new Output\ColorOutput("Hello <warn>{$name}</warn>!");
    }

    public function q2Action() {
        $choice = new Output\Choice('What do you want to do?');
        $choice->addOption('n', 'Noting');
        $choice->addOption('e', 'Everything');
        $choice->addOption('dk', 'I don\'t know');
        $choice->addOption('stfu!', 'Shut up :)');
        $yourChoice = $choice->ask()->getAnswer();
        return new Output\Output("Your choice was '{$yourChoice}'!");
    }

}
