<?php
namespace Shift1\Core\Request;

interface iRequest {

    /**
     * @abstract
     * @return void
     */
    public function assembleController();

    /**
     * @abstract
     * @return void
     */
    public function getRouter();

}