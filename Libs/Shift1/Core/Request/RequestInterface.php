<?php
namespace Shift1\Core\Request;

interface RequestInterface {

    /**
     * @abstract
     * @return string
     */
    function getAppRequest();

    /**
     * @abstract
     * @return string
     */
    function getAppRootUri();

    /**
     * @abstract
     * @return mixed
     */
    function parseCliArgs();

}