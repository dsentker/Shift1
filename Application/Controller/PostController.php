<?php
namespace Application\Controller;

use Shift1\Core\Response\Response;
use Shift1\Core\Exceptions as E;
use Shift1\Core\View\View;
use Shift1\Core\FrontController;
use Shift1\Core\Request\Request;

class PostController extends ParentController {

    public function init() {
        parent::init();

    }

    public function viewAction(\StdClass $post) {
        $this->view->post = $post;
        $this->view->foo = '<a href="#">AA</a> ';
        #print_r($this->view->render());
        return new Response($this->view);
    }



}