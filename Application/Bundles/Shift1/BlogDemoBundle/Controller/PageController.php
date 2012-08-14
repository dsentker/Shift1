<?php
namespace Bundles\Shift1\BlogDemoBundle\Controller;

use Shift1\Core\Response\Response;
use Shift1\Core\Request\Request;

class PageController extends ParentController {

    public function sidebarAction() {

        $this->view->data = 'A simple string';
        return new Response($this->view);
    }

}