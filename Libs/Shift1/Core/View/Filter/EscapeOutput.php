<?php
namespace Shift1\Core\View\Filter;

class EscapeOutput extends AbstractViewFilter  {

    /**
     * @return string|mixed
     */
    public function getVal() {
        return (\is_string($this->val)) ? \htmlspecialchars($this->val) : $this->val;
    }

}
