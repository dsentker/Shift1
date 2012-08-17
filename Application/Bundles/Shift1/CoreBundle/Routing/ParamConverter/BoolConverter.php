<?php
namespace Bundles\Shift1\CoreBundle\Routing\ParamConverter;

use Shift1\Core\Routing\ParamConverter\AbstractParamConverter;

class BoolConverter extends AbstractParamConverter {

    /**
     * @param mixed $value
     * @return string
     */
    public function getUriParam($value) {
        if(true === $value) {
            return 'true';
        } elseif(false === $value) {
            return 'false';
        }
        return \str_replace(' ', '-', $value);
    }

    public function getActionParam($identificator) {
        if('true' === $identificator) {
            return true;
        } elseif('false' === $identificator) {
            return false;
        }
        return $identificator;
    }


}
