<?php
namespace Shift1\Core\Autoloader;

use \Symfony\Component\ClassLoader;

require_once realpath(BASEPATH . '/Libs/Symfony/Component/ClassLoader/UniversalClassLoader.php');

class Autoloader extends ClassLoader\UniversalClassLoader {

    public function __construct() {

        $this->registerNamespace('Shift1', BASEPATH . '/Libs/');
        $this->registerNamespace('Application', BASEPATH . '/');
        $this->registerNamespace('Symfony', BASEPATH . '/Libs/');
        
    }
    
}