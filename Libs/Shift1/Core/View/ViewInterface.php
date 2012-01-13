<?php
namespace Shift1\Core\View;

interface ViewInterface {

    /** @todo check implementation details */

    /**
     * @abstract
     * @return string
     */
    public function render();

    /**
     * @abstract
     * @return void
     */
    function getViewFile();

    /**
     * @abstract
     * @return bool
     */
    function isThrowingExceptions();

}