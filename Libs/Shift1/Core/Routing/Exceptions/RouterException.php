<?php
namespace Shift1\Core\Routing\Exceptions;

class RouterException extends \Exception {

    const ROUTE_NOT_FOUND       = 0;
    const PASSCHECKER_INVALID   = 1;
    const ROUTE_HANDLER_MISSING = 2;

}
