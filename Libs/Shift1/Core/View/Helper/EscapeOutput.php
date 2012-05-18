<?php
namespace Shift1\Core\View\Helper;

class EscapeOutput  {

    /**
     * @param mixed $in
     * @return string
     */
    public function escapeHtml($in) {
        return (\is_string($in)) ? \htmlspecialchars($in) : $in;
    }

}
