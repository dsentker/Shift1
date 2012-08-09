<?php
namespace Application\Controller;

use Shift1\Core\Controller\Controller;
use Shift1\Core\Response\Response;

class ParentController extends Controller {

    public function indexAction() {
        return 'indx';
    }

    public function init() {
        parent::init();
    }

    public function notFoundAction() {
        $params = $this->getParams();
        return new Response("Action '{$params['_action']}' not found in {$params['_controller']}");
    }

}