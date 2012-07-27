<?php
namespace Shift1\Core\Autoloader;

use \Symfony\Component\ClassLoader;

class Autoloader extends ClassLoader\UniversalClassLoader {

    public function __construct() {

        $this->registerNamespace('Shift1', BASEPATH . '/Libs/');
        $this->registerNamespace('Application', BASEPATH . '/');
        $this->registerNamespace('Symfony', BASEPATH . '/Libs/vendor/');
        
    }
    
}