<?php
namespace Shift1\Core\Routing\Exceptions;

class RouteParamException extends \Exception {

    const PARAM_NAME_NOT_FOUND          = 0;
    const PARAM_POSITION_NOT_FOUND      = 1;

}
