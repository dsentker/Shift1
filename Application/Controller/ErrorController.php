<?php
namespace Application\Controller;

use Shift1\Core\Response\Response;
use Shift1\Core\View\View;
use Shift1\Core\Response\Header\Header;

class ErrorController extends ParentController {

    public function indexAction() {
        return new Response($this->view, new Header(404));
    }

}