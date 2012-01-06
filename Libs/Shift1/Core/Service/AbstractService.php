<?php
namespace Shift1\Core\Service;

use Shift1\Core\InternalFilePath;
use Shift1\Core\Exceptions as Exception;

abstract class AbstractService implements ServiceInterface {

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $path;

    /**
     * @ var array
     */
    protected $necessitates = array();

    /**
     * @var array
     */
    protected $injectedServices = array();

    /**
     * @var array
     */
    protected $constructorArgs = array();

    /**
     * @var bool
     */
    static public $isSingleton = false;

    /**
     * @param string $ns
     * @param null|string $path
     * @return void
     */
    public function setClassNamespace($ns, $path = null) {
        $this->namespace = $ns;
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getClassNamespace() {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @throws \Shift1\Core\Exceptions\ClassNotFoundException|\Shift1\Core\Exceptions\FileNotFoundException
     * @return mixed
     */
    public function getRessource() {

        if(empty($this->path)) {
            throw new Exception\ClassNotFoundException($this->namespace);
        }

        $ressourcePath = new InternalFilePath($this->getPath());
        if(\file_exists($ressourcePath)) {
            require_once $ressourcePath;
        } else {
            throw new Exception\FileNotFoundException($ressourcePath);
        }
        
    }

    /**
     * @param object $serviceInstance
     * @return void
     */
    public function prepare(&$serviceInstance) {
        
    }

    /**
     * @return void
     */
    public function initialize() {

    }

    /**
     * @param array $args
     * @return void
     */
    public function setConstructorArgs(array $args) {
        $this->constructorArgs = $args;
    }

    /**
     * @return array
     */
    public function getConstructorArgs() {
        return $this->constructorArgs;
    }

    /**
     * @throws \Shift1\Core\Exceptions\ServiceException
     * @return object
     */
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

    protected function necessitate($service) {
        $this->necessitates[] = $service;
    }

    public function getNecessitatedServices() {
        return $this->necessitates;
    }

    public function hasNecessitatesServices() {
        return (\count($this->necessitates) > 0);
    }

    public function inject($id, $service) {
        $this->injectedServices[$id] = $service;
    }

    public function get($serviceId) {
        if(empty($this->injectedServices[$serviceId])) {
           throw new Exception\ServiceException("Service not found: {$serviceId}");
        }
        return $this->injectedServices[$serviceId];
    }

    /**
     * @static
     * @return bool
     */
    static public function getIsSingleton() {
        return static::$isSingleton;
    }

}