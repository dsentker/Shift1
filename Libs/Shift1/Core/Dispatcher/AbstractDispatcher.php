<?php

namespace Shift1\Core\Dispatcher;

use Shift1\Core\Shift1Object;
use Shift1\Core\Request\iRequest;

abstract class AbstractDispatcher extends Shift1Object implements iDispatcher {

    protected $request;

    public function __construct(iRequest $request) {
        $this->request = $request;
    }


    public function getRequest() {
        return $this->request;
    }



}

?>
