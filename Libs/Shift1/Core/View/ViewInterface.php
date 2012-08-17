<?php
namespace Shift1\Core\View;

use Shift1\Core\View\VariableSet\VariableSetInterface;

interface ViewInterface {

    /**
     * @abstract
     * @return string
     */
    #function render();

    /**
     * @abstract
     * @return \Shift1\Core\InternalFilePath
     */
    function getViewFile();

    /**
     * @abstract
     * @return bool
     */
    function isThrowingExceptions();

    /**
     * @abstract
     * @return \Shift1\Core\View\VariableSet\VariableSetInterface;
     */
    function getVariableSet();


    /**
     * @abstract
     * @param string $templateFile
     * @return string
     */
    function renderTemplate($templateFile);

    /**
     * @abstract
     * @return void
     */
    function disableExceptions();

}