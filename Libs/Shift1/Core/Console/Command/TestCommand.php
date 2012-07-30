<?php
namespace Shift1\Core\Console\Command;

class TestCommand extends AbstractCommand {


    public function execute() {
        echo 'Hello ' . $this->getParameter('name');
    }

}
