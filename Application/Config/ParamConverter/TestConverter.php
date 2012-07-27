<?php
namespace Application\Config\ParamConverter;

use Shift1\Core\Router\ParamConverter\AbstractParamConverter;

class TestConverter extends AbstractParamConverter {

    public function getUriParam($stdClass) {
        return $stdClass->slug;
    }

    public function getActionParam($identificator) {
        $mock = new \StdClass();
        $mock->title = 'I am a blog post';
        $mock->slug = 'i-am-a-blog-post';
        $mock->body = 'Hello World. I am a content from a fictional blog post.';
        $mock->author = 'Daniel Sentker';
        $mock->permalink = '<a href="http://www.google.de">Permalink</a>';
        return $mock;
    }


}
