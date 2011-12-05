<?php
namespace Application\Controller;

class FooController extends ParentController {

    public function indexAction() {
        print_r($this->getParams());
        return 'index von fooController';
    }

    public function barAction() {
        echo 'Hallo Welt! <pre>';
        print_r($this->getParams());
    }

}


?>
