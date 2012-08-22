<?php
namespace Bundles\Shift1\BlogDemoBundle\Controller;

use Bundles\Shift1\BlogDemoBundle\Events\BlogViewEvent;
use Bundles\Shift1\BlogDemoBundle\Models\Blogpost;
use Shift1\Core\Response\Response;

class PostController extends ParentController {

    public function init() {
        parent::init();
    }

    public function viewAction(Blogpost $post) {

        $this->view->post = $post;
        $this->view->foo = '<a href="#">A<'; // sic! to test the html output escaper
        $this->view->params = $this->getParams();

        $dispatcher = $this->getContainer()->get('eventDispatcher');
        $dispatcher->dispatch('kernel.response', new BlogViewEvent($this->view));

        return new Response($this->view);
    }

    /* MARKER_APPEND */

}