<?php
namespace Shift1\Core\Dispatcher;

interface iDispatcher {

    public function getRequest();
    public function dispatch();

}
?>
