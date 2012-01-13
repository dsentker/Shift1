<?php
namespace Application\Services\ViewHelper;

use Shift1\Core\Service\AbstractService;

class EscapeOutputService extends AbstractService  {

    public static $isSingleton = true;

    public static function escape($var) {
        return (\is_string($var)) ? \htmlspecialchars($var) : $var;
    }

}