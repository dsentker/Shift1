<?php
namespace Shift1\Core\View;

interface iView {

    /**
     * @abstract
     * @return string
     */
    public function render();

    /**
     * @abstract
     * @param bool $throw
     * @return string
     */
    public function getContent($throw = true);
}
