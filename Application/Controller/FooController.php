<?php
namespace Application\Controller;

use Shift1\Core\Response\Response;

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
        return new Response($this->view->render());
    }

}
