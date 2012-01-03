<?php
namespace Application\Controller;

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
        echo 'Test from ::testFooAction()';
    }

}
