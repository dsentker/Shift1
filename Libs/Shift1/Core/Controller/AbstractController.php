<?php
namespace Shift1\Core\Controller;

use Shift1\Core\Exceptions\ControllerException;
use Shift1\Core\Service\Container\ServiceContainer;
use Shift1\Core\Controller\Factory\ControllerFactory;
use Shift1\Core\Request\RequestInterface;

abstract class AbstractController implements ControllerInterface  {

    /**
     * @var string
     */
    static $actionDefault = 'index';

    /**
     * @var string
     */
    static $actionNotFound = 'notFound';

    /**
     * @var array
     */
    protected $params = array();

    /**
     * @var ServiceContainer
     */
    protected $serviceContainer;

    /**
     * @param array $params
     * @final
     */
    final public function __construct(array $params = array()) {
        $this->params = $params;
    }

    /**
     * @return void
     */
    public function init() {
        $this->initView();
    }

    /**
     * @param array $params
     * @return void
     */
    public function setParams(array $params) {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * @param string $paramIdentifier
     * @param mixed $defaultReturn
     * @return mixed
     */
    protected function getParam($paramIdentifier, $defaultReturn = false) {
        return ($this->hasParam($paramIdentifier)) ? $this->params[$paramIdentifier] : $defaultReturn;
    }

    /**
     * @param string $paramIdentifier
     * @return bool
     */
    protected function hasParam($paramIdentifier) {
        return isset($this->params[$paramIdentifier]);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function addParam($key, $value) {
        $this->params[$key] = $value;
    }

    /**
     * @static
     * @return string
     */
    public static function getDefaultActionName() {
        return self::$actionDefault;
    }

    /**
     * @static
     * @return string
     */
    public static function getNotFoundActionName() {
        return self::$actionNotFound;
    }

    /**
     * @return string
     */
    public function getControllerName() {
        $fqClassname = \get_class($this);
        $parts = \explode('\\', $fqClassname);
        return \array_pop($parts);
    }


    /**
     * @return \Shift1\Core\View\View
     */
    public function getView() {
        return $this->view;
    }

    /**
     * @param \Shift1\Core\Service\Container\ServiceContainer $container
     * @return void
     */
    public function setContainer(ServiceContainer $container) {
        $this->serviceContainer = $container;
    }

    /**
     * @return \Shift1\Core\Service\ServiceContainer
     */
    public function getContainer() {
        return $this->serviceContainer;
    }

    /**
     * Provides a symfony2-style getter for a
     * direct access to a specific service from
     * a controller
     * 
     * @param string $serviceName
     * @return mixed The Service Object
     */
    public function get($serviceName) {
        return $this->getContainer()->get($serviceName);
    }

    /**
    /**
     * @return \Shift1\Core\Shift1\Core\Request\RequestInterface
     */
    public function getRequest() {
        return $this->getContainer()->get('shift1.request');
    }

    /**
     * @param string $controller The Controller name (no suffix)
     * @param string $action  The Action name (no suffix)
     * @param array  $params The params
     * @return mixed|\Shift1\Core\Response\ResponseInterface
     */
    public function internalRequest($controller, $action, $params) {
        $controllerFactory = $this->get('shift1.controllerFactory');
        $controllerAggregate = $controllerFactory->createController($controller, $action, $params);
        return $controllerAggregate->run();
    }

    /**
     * @abstract
     * @return void
     */
    abstract public function initView();

    
}