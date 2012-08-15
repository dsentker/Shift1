<?php
namespace Bundles\Shift1\BlogDemoBundle\ParamConverter;

use Shift1\Core\Router\ParamConverter\AbstractParamConverter;
use Bundles\Shift1\BlogDemoBundle\Models\Blogpost;

class BlogpostConverter extends AbstractParamConverter {

    public function getUriParam($blogpost) {
        return $blogpost->slug;
    }

    public function getActionParam($identificator) {
        // [...] findById($identificator);
        return new Blogpost;
    }


}
