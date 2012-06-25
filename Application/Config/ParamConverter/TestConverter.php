<?php
namespace Application\Config\ParamConverter;

use Shift1\Core\Router\ParamConverter\AbstractParamConverter;

class TestConverter extends AbstractParamConverter {

    public function getUriParam($stdClass) {
        return $stdClass->foobar;
    }

    public function getActionParam($identificator) {
        $class = new \StdClass();
        $class->foobar = 'This is a converter test';
        return $class;
    }


}
