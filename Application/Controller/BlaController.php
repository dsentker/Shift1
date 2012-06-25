<?php
namespace Application\Controller;

use Shift1\Core\Response\Response;
use Shift1\Core\Exceptions as E;
use Shift1\Core\View\View;
use Shift1\Core\FrontController;
use Shift1\Core\Request\Request;

class BlaController extends ParentController {

    public function init() {
        parent::init();
        /** @var \Shift1\Log\Logger $logger */
        #$logger = FrontController::getInstance()->getServiceContainer()->get('Log');
        #$logger->log('IndexController initiated', 'debug');
    }

    public function indexAction() {
        return new Response('<pre>' . print_r($this->getParams(), 1));
    }



}