<?php
namespace Bundles\Shift1\CoreBundle\Controller;

use Shift1\Core\Response\Response;
use Shift1\Core\View\View;
use Shift1\Core\Response\Header\Header;
use Shift1\Core\Controller\Controller;

class ErrorController extends Controller {

    public function notfoundAction() {
        return new Response($this->getView()->render(), new Header(404));
    }

}