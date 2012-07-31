<?php
namespace Shift1\Core\Console\Command;

use Shift1\Core\Console\Output;

class TestCommand extends AbstractCommand {


    public function execute() {

        $dialog = new Output\Dialog('Wie heißt du?');
        $dialog->ask();
        $name = $dialog->getAnswer();
        return new Output\ColorOutput("Hallo <warn>{$name}</warn>!");

    }

}
