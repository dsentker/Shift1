<?php
namespace Application\Controller;

use Shift1\Core\Response\Response;
use Shift1\Core\View\View;
use Shift1\Core\Response\Header\Header;

/**
 * ErrorController
 *
**/

class ErrorController extends ParentController {

    public function indexAction() {
        return new Response($this->getView()->render(), new Header(404));
    }

}