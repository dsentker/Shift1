<?php
namespace Shift1\Core\Service;

use Shift1\Core\InternalFilePath;
use Shift1\Core\Exceptions as Exception;
use Shift1\Core\Shift1Object;

abstract class AbstractService extends Shift1Object implements iService {
    
    protected $namespace;
    
    protected $path;
    
    protected $constructorArgs = array();

    static public $isSingleton = false;
    
    public function setClassNamespace($ns, $path = null) {
        $this->namespace = $ns;
        $this->path = $path;
    }
    
    public function getClassNamespace() {
        return $this->namespace;
    }
    
    public function getPath() {
        return $this->path;
    }
    
    public function getRessource() {

        if(empty($this->path)) {
            throw new Exception\ClassNotFoundException($this->namespace);
        }

        $ressourcePath = new InternalFilePath('Application/' . $this->getPath());
        if(\file_exists($ressourcePath)) {
            require_once $ressourcePath;
        } else {
            throw new Exception\FileNotFoundException($ressourcePath);
        }
        
    }
    
    public function prepare(&$serviceInstance) {
        
    }
    
    public function setConstructorArgs(array $args) {
        $this->constructorArgs = $args;
    }
    
    public function getConstructorArgs() {
        return $this->constructorArgs;
    }
    
    public function getInstance() {
        
        $serviceClassName = $this->getClassNamespace();
        $constructorArgs  = $this->getConstructorArgs();

        if(empty($this->namespace)) {
            throw new Exception\ServiceException('No namespace target defined for ' . \get_class(($this)));
        }
        
        if(!\class_exists($serviceClassName)) {
            $this->getRessource();
        }
        
        if(empty($constructorArgs)) {
            $instance = new $serviceClassName;
        } else {
            $reflectedService = new \ReflectionClass($serviceClassName);
            $instance = $reflectedService->newInstanceArgs($constructorArgs);
        }
        
        $this->prepare($instance);
        return $instance;
    }

}
?>