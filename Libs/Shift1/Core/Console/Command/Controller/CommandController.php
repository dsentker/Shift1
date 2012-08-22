<?php
namespace Shift1\Core\Console\Command\Controller;

use Shift1\Core\Controller\AbstractController;

class CommandController extends AbstractController {

    /**
     * @return void
     */
    public function init() {

    }

    /**
     * @return mixed
     */
    public function getConsoleArgs() {
        return $this->getRequest()->parseCliArgs();
    }

}
