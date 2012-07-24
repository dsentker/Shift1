<?php
namespace Shift1\Core\View\Filter;

class Ucfirst extends AbstractViewFilter {

    /**
     * @return string|mixed
     */
    public function getVal() {
        return (\is_string($this->val)) ? \ucfirst($this->val) : $this->val;
    }

}
