<?php
namespace Application\Controller;

use Shift1\Core\Response\Response;
use Shift1\Core\Request\Request;

class FooController extends ParentController {

    public function indexAction() {
        print_r($this->getParams());
        return 'index from fooController';
    }

    public function barAction() {
        echo 'Hello World! <pre>';
        print_r($this->getParams());
    }

    public function testFooAction() {
        $req = Request::newInternal('/doubleAttack', $this->getRequest());
        $response = $this->internalRequest($req);
        $this->view->insertme = $response;
        return new Response($this->view->render());
    }

    public function doublemvcAction() {
        return new Response($this->view->render());
    }

}
