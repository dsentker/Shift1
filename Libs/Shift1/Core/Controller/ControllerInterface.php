<?php
namespace Shift1\Core\Controller;

use Shift1\Core\FrontController;

interface ControllerInterface {

    /**
     * @abstract
     * @param array $params
     */
    function __construct(array $params = array());

    /**
     * @abstract
     * @param array $params
     * @return void
     */
    function setParams(array $params);

    /**
     * @abstract
     * @return void
     */
    function getParams();

    /**
     * @abstract
     * @return void
     */
    function init();

}
