<?php
namespace Application\Controller;


use Application\Events\ViewEvent;

use Shift1\Core\Response\Response;
use Shift1\Core\Exceptions as E;
use Shift1\Core\View\View;
use Shift1\Core\FrontController;
use Shift1\Core\Request\Request;

/**
 *
 * PostController
 *
**/

class PostController extends ParentController {

    public function init() {
        parent::init();
    }

    public function viewAction(\StdClass $post) {

        $this->view->post = $post;
        $this->view->foo = '<a href="#">A<'; // sic! to test the html output escaper

        $dispatcher = $this->getContainer()->get('EventDispatcher');
        $dispatcher->dispatch('kernel.response', new ViewEvent($this->view));

        return new Response($this->view);
    }

    /* MARKER_APPEND */

}