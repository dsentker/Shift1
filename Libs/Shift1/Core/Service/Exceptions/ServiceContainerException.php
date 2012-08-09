<?php
namespace Shift1\Core\Service\Exceptions;

class ServiceContainerException extends \RuntimeException {

    const LOCATOR_NOT_FOUND     = 0;
    const BAD_INTERFACE         = 1;
    const SERVICE_NOT_RUNNING   = 3;


}
