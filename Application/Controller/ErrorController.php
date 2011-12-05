<?php
namespace Application\Controller;

use Shift1\Core\Response\Response;

class ErrorController extends ParentController {

    public function indexAction() {
        $params = $this->getParams();
        return new Response("Controller '{$params['_controller']}::{$params['_action']}' not found.<br /><pre>" . \var_export($params, 1));
    }

}

?>