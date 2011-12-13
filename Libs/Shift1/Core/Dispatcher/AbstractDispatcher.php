<?php
namespace Shift1\Core\Dispatcher;

use Shift1\Core\Shift1Object;
use Shift1\Core\Request\iRequest;

abstract class AbstractDispatcher extends Shift1Object implements iDispatcher {

    /**
     * @var \Shift1\Core\Request\iRequest
     */
    protected $request;

    /**
     * @param \Shift1\Core\Request\iRequest $request
     */
    public function __construct(iRequest $request) {
        $this->request = $request;
    }

    /**
     * @return \Shift1\Core\Request\iRequest
     */
    public function getRequest() {
        return $this->request;
    }

}