<?php
namespace Shift1\Core\View\Filter;

interface ViewFilterInterface {

    /**
     * @abstract
     * @param string $val
     * @return \Shift1\Core\View\Filter\ViewFilterInterface
     */
    function setVal($val);

    /**
     * @abstract
     * @return void
     */
    function getVal();

}
