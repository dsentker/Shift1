<?php
namespace Shift1\Core\Service\Locator;

use Shift1\Core\InternalFilePath;
use Shift1\Core\Service\Exceptions\ServiceLocatorException;

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
     * @throws \Shift1\Core\Service\Exceptions\ServiceLocatorException
     * @return bool
     */
    public function getRessource() {

        if(empty($this->path)) {
            throw new ServiceLocatorException("Could not load '{$this->namespace}': Autoloading failed, and locator does not provide a file path.", ServiceLocatorException::NO_PATH_PROVIDED);
        }

        $ressourcePath = new InternalFilePath($this->getPath());

        if(!\file_exists($ressourcePath->getAbsolutePath())) {
            throw new ServiceLocatorException("Could not load '{$this->namespace}': '{$ressourcePath->getAbsolutePath()}' is not a valid path.", ServiceLocatorException::PATH_ERROR);
        }

        require_once $ressourcePath->getAbsolutePath();
        return true;
        
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
     * @return object
     * @throws ServiceLocatorException
     */
    public function getInstance() {
        
        $serviceClassName = $this->getClassNamespace();
        $constructorArgs  = $this->getConstructorArgs();

        if(empty($this->namespace)) {
            throw new ServiceLocatorException('Instance failed for ' . $this->getId() . ': No Namespace defined.', ServiceLocatorException::NAMESPACE_ERROR);
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
     * @param string $service
     * @throws ServiceLocatorException
     */
    protected function dependsOn($service) {

        switch(true) {
            case \is_array($service):
                foreach($service as $serviceItem) {
                    $this->dependsOn($serviceItem);
                }
                break;

            case \is_string($service):
                $this->necessitates[] = $service;
                break;

            default:
                throw new ServiceLocatorException('Requested service name must be a string!', ServiceLocatorException::UNKNOWN_SERVICE);
            
        }
    }

    /**
     * @return array
     */
    public function getDependentServices() {
        return $this->necessitates;
    }

    /**
     * @return bool
     */
    public function hasDependentServices() {
        return (\count($this->necessitates) > 0);
    }

    /**
     * @param string $id
     * @param string $service
     */
    public function injectService($id, $service) {
        $this->injectedServices[$id] = $service;
    }

    /**
     * @param $serviceId
     * @return mixed
     * @throws ServiceLocatorException
     */
    public function getService($serviceId) {
        if(empty($this->injectedServices[$serviceId])) {
           throw new ServiceLocatorException("Service, requested by {$this->getId()}, not found: {$serviceId}", ServiceLocatorException::UNKNOWN_SERVICE);
        }
        return $this->injectedServices[$serviceId];
    }

    /**
     * @return bool
     */
    public function getIsSingleton() {
        return static::$isSingleton;
    }

    /**
     * @return string
     */
    public function getId() {
        $locatorNamespaceParts = \explode('\\', \get_class($this));
        $locatorName = \array_pop($locatorNamespaceParts);

        // remove the "Locator" suffix
        $suffixPos = \strrpos($locatorName, self::SERVICE_LOCATOR_SUFFIX);
        return \substr($locatorName, 0, $suffixPos);

    }

}