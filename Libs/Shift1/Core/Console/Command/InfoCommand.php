<?php
namespace Shift1\Core\Console\Command;

class InfoCommand extends AbstractCommand {


    public function execute() {

        echo \DIRECTORY_SEPARATOR . PHP_EOL;
        echo \PHP_OS . PHP_EOL;
        echo \php_uname() . PHP_EOL;



    }

}
