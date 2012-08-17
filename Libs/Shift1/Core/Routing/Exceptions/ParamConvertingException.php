<?php
namespace Shift1\Core\Routing\Exceptions;

class ParamConvertingException extends \Exception {

    const PARAM_CONVERTER_CLASS_INVALID     = 0;
    const PARAM_CONVERTER_INTERFACE_INVALID = 1;

}