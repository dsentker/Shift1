<?php
namespace Shift1\Core\Config\Exceptions;

class AdjustmentRequestException extends \Exception {

    const SUBJECT_INVALID               = 0;
    const SUBJECT_EMPTY                 = 1;
    const VALIDATOR_CALLBACK_INVALID    = 2;

}
