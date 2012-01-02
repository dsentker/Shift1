<?php
namespace Shift1\Core\View;

interface ViewInterface {

    /**
     * @abstract
     * @return string
     */
    public function render();

    /**
     * @abstract
     * @return string
     */
    public function getContent();
}