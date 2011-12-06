<?php
namespace Shift1\Core;
use \Shift1\Core\App;

abstract class Shift1Object {

    protected final function getApp() {
        return App::getInstance();
    }

}
?>