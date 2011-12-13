<?php
namespace Shift1\Core\FrontController;

interface iFrontController {

    /**
     * @abstract
     * @return void
     */
    public function getDispatcher();

    /**
     * @abstract
     * @return void
     */
    public function run();

}