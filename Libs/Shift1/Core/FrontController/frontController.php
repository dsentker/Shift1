<?php
namespace Shift1\Core\FrontController;

use Shift1\Core\Dispatcher\Dispatcher;

/*
 * This is a Controller which could acts as inheritance Controller
 * for MVC (HMVC) structures. This Class is callable thru an
 * existing Controller (e.g. FrontController)
 */

class FrontController extends AbstractFrontController {

    public function __construct(Dispatcher $dispatcher) {
        $this->setDispatcher($dispatcher);
    }
   

}
?>
