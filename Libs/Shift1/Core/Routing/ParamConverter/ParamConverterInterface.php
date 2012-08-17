<?php
namespace Shift1\Core\Routing\ParamConverter;

interface ParamConverterInterface {

    /**
     * @abstract
     * @param string $identificator
     * @return mixed
     */
    function getActionParam($identificator);

    /**
     * @abstract
     * @param mixed $value
     * @return string
     */
    function getUriParam($value);

}
