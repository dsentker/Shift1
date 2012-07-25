<?php
namespace Shift1\Core\Service\Locator;

use Shift1\Core\InternalFilePath;
use Shift1\Core\Exceptions as Exception;

abstract class AbstractServiceLocator implements ServiceLocatorInterface {

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
            throw new Exception\ServiceException('No instance available for ' . \get_class($this) . '. No Namespace defined.');
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

    /**
     * @throws \Shift1\Core\Exceptions\ServiceException
     * @param string|array $service
     * @return void
     */
    protected function necessitate($service) {

        switch(true) {
            case \is_array($service):
                foreach($service as $serviceItem) {
                    $this->necessitate($serviceItem);
                }
                break;

            case \is_string($service):
                $this->necessitates[] = $service;
                break;

            default:
                throw new Exception\ServiceException('Unknown Service necessitated by ' . \get_class($this));
            
        }
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