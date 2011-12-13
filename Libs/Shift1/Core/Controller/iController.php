<?php
namespace Shift1\Core\Controller;

interface iController {

    /**
     * @abstract
     * @param array $params
     */
    public function __construct(array $params = array());

    /**
     * @abstract
     * @param array $params
     * @return void
     */
    public function setParams(array $params);

    /**
     * @abstract
     * @return void
     */
    public function getParams();

    /**
     * @abstract
     * @return void
     */
    public function init();

}
