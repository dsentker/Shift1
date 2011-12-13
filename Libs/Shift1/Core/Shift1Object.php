<?php
namespace Shift1\Core;

use \Shift1\Core\App;

abstract class Shift1Object {

    /**
     * @return \Shift1\Core\App;
     */
    protected final function getApp() {
        return App::getInstance();
    }

}