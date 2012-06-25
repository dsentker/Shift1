<?php
namespace Application\Config\ParamConverter;

use Shift1\Core\Router\ParamConverter\AbstractParamConverter;

class UnderscoreConverter extends AbstractParamConverter {

    /**
     * @param mixed $value
     * @return string
     */
    public function getUriParam($value) {
        return \str_replace(' ', '_', $value);
    }

    public function getActionParam($identificator) {
        return \str_replace('_', ' ', $identificator);
    }


}
