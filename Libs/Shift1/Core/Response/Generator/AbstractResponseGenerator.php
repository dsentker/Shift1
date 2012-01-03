<?php
namespace Shift1\Core\Response\Generator;

abstract class AbstractResponseGenerator implements ResponseGeneratorInterface {

    /**
     * @static
     * @return self
     */
    public static function factory() {
        return new static();
    }

}