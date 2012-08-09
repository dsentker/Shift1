<?php
namespace Shift1\Core\Service\Exceptions;

class ServiceLocatorException extends \RuntimeException {

    const NO_PATH_PROVIDED      = 0;
    const PATH_ERROR            = 1;
    const NAMESPACE_ERROR       = 2;
    const UNKNOWN_SERVICE       = 3;



}
