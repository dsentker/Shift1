<?php
namespace Shift1\Core\FrontController\Exceptions;
 
class FrontControllerException extends \LogicException {

    const REQUEST_NOT_VALID         = 0;
    const RESPONSE_NOT_VALID        = 1;
    const CLI_NOT_RUNNING           = 2;

}
