<?php
namespace Shift1\Core\Context;

use \ArrayObject;

class Context extends ArrayObject {

    public function __construct() {
        $this->setFlags((self::ARRAY_AS_PROPS));
    }

}
