<?php
namespace Application\Config\ParamConverter;

use Shift1\Core\Router\ParamConverter\AbstractParamConverter;

class TestConverter extends AbstractParamConverter {

    public function getUriParam($stdClass) {
        return $stdClass->foobar;
    }

    public function getActionParam($identificator) {
        $class = new \StdClass();
        $class->title = 'I am a blog post<a href="a">a</a>';
        $class->body = 'Hello World. I am a content from a fictional blog post.';
        $class->author = 'Daniel Sentker';
        return $class;
    }


}
