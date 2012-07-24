<?php
namespace Shift1\Core\View\Filter;

abstract class AbstractViewFilter implements ViewFilterInterface {

    protected $val;

    /**
     * @param string $val
     * @return AbstractViewFilter
     */
    public function setVal($val) {
        $this->val = $val;
        return $this;
    }

    /**
     * @return string|mixed
     */
    public function getVal() {
        return $this->val;
    }

}
