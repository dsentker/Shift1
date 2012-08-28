<?php
namespace Shift1\Core\Bundle\Exceptions;

class ConvergerException extends \RuntimeException {

    const BUNDLE_DEFINITION_ERROR   = 0;
    const BUNDLE_MANAGER_NOT_FOUND  = 1;
    const BUNDE_SET_INVALID         = 2;

}
