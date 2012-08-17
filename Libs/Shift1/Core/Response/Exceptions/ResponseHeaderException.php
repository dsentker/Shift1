<?php
namespace Shift1\Core\Response\Exceptions;

class ResponseHeaderException extends \Exception {

    const HEADER_STRING_INALID      = 0;
    const HEADERS_ALREADY_SENT      = 1;

}