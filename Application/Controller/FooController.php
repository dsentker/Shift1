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
        $response = $this->internalRequest('Foo', 'doublemvc', array('Foo' => 'bar'));
        #var_dump($response->getContent());
        $this->view->insertme = $response->getContent();
        return new Response($this->view->render());
    }

    public function doublemvcAction() {
        return new Response($this->view->render());
    }

    public function paramConvertAction(\StdClass $test) {
        return new Response($test->foobar);
    }

}
