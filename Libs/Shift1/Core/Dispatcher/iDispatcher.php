<?php
namespace Shift1\Core\Dispatcher;

interface iDispatcher {

    /**
     * @abstract
     * @return void
     */
    public function getRequest();

    /**
     * @abstract
     * @return void
     */
    public function dispatch();

}