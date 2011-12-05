<?php
namespace Shift1\Core\Response\Generator;

use Shift1\Core\Shift1Object;

abstract class AbstractResponseGenerator extends Shift1Object implements iResponseGenerator {
    
    public static function factory() {
        return new static();
    }

}

?>
