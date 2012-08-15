<?php
namespace Bundles\Shift1\BlogDemoBundle\Controller;


use Bundles\Shift1\BlogDemoBundle\Events\BlogViewEvent;

use Shift1\Core\Response\Response;

class PostController extends ParentController {

    public function init() {
        parent::init();
    }

    public function viewAction(\StdClass $post) {

        $this->view->post = $post;
        $this->view->foo = '<a href="#">A<'; // sic! to test the html output escaper

        $dispatcher = $this->getContainer()->get('eventDispatcher');
        $dispatcher->dispatch('kernel.response', new BlogViewEvent($this->view));
#throw new \Exception("TEST");
        return new Response($this->view);
    }

    /* MARKER_APPEND */

}