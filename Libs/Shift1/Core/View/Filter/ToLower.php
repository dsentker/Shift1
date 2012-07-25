<?php
namespace Shift1\Core\View\Filter;

class Tolower extends AbstractViewFilter {

    /**
     * @return string|mixed
     */
    public function getVal() {
        return (\is_string($this->val)) ? \mb_strtolower($this->val) : $this->val;
    }

}
