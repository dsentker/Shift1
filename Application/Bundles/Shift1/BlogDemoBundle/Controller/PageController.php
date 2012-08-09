<?php
namespace Application\Controller;

use Shift1\Core\Response\Response;
use Shift1\Core\Request\Request;

class PageController extends ParentController {

    public function sidebarAction() {

        $this->view->data = 'A simple string';
        #die(print_r($this->view->getVariableSet()->data));
        return new Response($this->view);
    }



}
