<?php
namespace Shift1\Core\Controller\Factory;

use Shift1\Core\Response\ResponseInterface;
use Shift1\Core\FrontController;
use Shift1\Core\Service\ContainerAccess;
use Shift1\Core\Service\Container\ServiceContainerInterface;

class ControllerFactory implements ControllerFactoryInterface, ContainerAccess {

    /**
     * @var \Shift1\Core\Config\Manager\ConfigManagerInterface
     */
    protected $config;

    /**
     * @var array
     */
    protected $params = array();

    /**
     * @var string
     */
    protected $actionName;

    /**
     * @var string
     */
    protected $controllerName;

    /**
     * @var ServiceContainerInterface
     */
    protected $container;

    public function __construct($config) {
        $this->config = $config;
    }

    public function setContainer(ServiceContainerInterface $container) {
        $this->container = $container;
    }

    public function getContainer() {
        return $this->container;
    }

    /**
     * @return \Shift1\Core\Controller\Factory\ControllerAggregate
     */
    public function getController() {

        $controllerNamespace = $this->config->namespace;
        $controllerName = \ucfirst($this->getControllerName());
        $controllerFqNs = $controllerNamespace . $controllerName . self::CONTROLLER_SUFFIX;
        
        if(empty($controllerName)) {
            $controllerName = $this->config->defaultController;
            $controllerSuffixed = $controllerNamespace . $controllerName . self::CONTROLLER_SUFFIX;
            return $this->getControllerInstance($controllerNamespace . $controllerName, $controllerSuffixed::getDefaultActionName());
        }

        if(!\class_exists($controllerFqNs)) {
            $controllerName = $this->config->errorController;
            $controllerSuffixed = $controllerNamespace . $controllerName . self::CONTROLLER_SUFFIX;
            return $this->getControllerInstance($controllerNamespace . $controllerName, $controllerSuffixed::getDefaultActionName());
        }

        return $this->getControllerInstance($controllerNamespace . $controllerName, $this->getActionName(), $this->getParams());

    }

    /**
     * @param string $controllerName
     * @param string $actionName
     * @param array $params
     * @return \Shift1\Core\Controller\Factory\ControllerAggregate
     */
    protected function getControllerInstance($controllerName, $actionName, array $params = array()) {

        $controllerNameSuffixed = $controllerName . self::CONTROLLER_SUFFIX;
        $actionNameSuffixed = $actionName . self::ACTION_SUFFIX;

        $rfController = new \ReflectionClass($controllerNameSuffixed);

        /** @var $controller \Shift1\Core\Controller\AbstractController */
        $controller = $rfController->newInstanceArgs(array($params));

        if(! \method_exists($controller, $actionNameSuffixed)) {
            $actionName = $controller::getNotFoundActionName();
            $actionNameSuffixed = $actionName . self::ACTION_SUFFIX;
        }

        $controllerNameParts = \explode('\\', $controllerName);
        
        
        $dispatched = array(
            'class' => \array_pop($controllerNameParts), // Just the last piece
            'action' => $actionName,
        );

        $controller->addParam('_dispatched', $dispatched);
        $controller->setContainer($this->getContainer());
        $actionParams = $this->mapParamsToActionArgs($params, new \ReflectionMethod($controller, $actionNameSuffixed));

        return new ControllerAggregate($controller, $actionNameSuffixed, $actionParams);
    }

    /**
     * @param $params
     * @param \ReflectionMethod $action
     * @return array
     */
    protected function mapParamsToActionArgs($params, \ReflectionMethod $action) {
        $actionParams = array();

        foreach($action->getParameters() as $param) {
            /** @var $param \ReflectionParameter */
            $paramPosition = $param->getPosition();

            if(isset($params[$param->getName()])) {
                $actionParams[$paramPosition] = $params[$param->getName()];
            } else {
                if($param->isDefaultValueAvailable()) {
                    /*
                     * Note that there is a strict behaviour in php's \ReflectionParameter->getDefaultValue().
                     * If the current default-value-parameter preprends an parameter without a default value,
                     * getDefaultValue() will return always FALSE.
                     * @see http://de3.php.net/manual/en/reflectionparameter.isdefaultvalueavailable.php#105207
                     */
                    $actionParams[$paramPosition] = $param->getDefaultValue();
                } else {
                    $actionParams[$paramPosition] = null;
                }
            }
        }

        return $actionParams;
    }

    public function setControllerName($controller) {
        $this->controllerName = $controller;
    }

    public function getControllerName() {
        return $this->controllerName;
    }

    public function setActionName($action) {
        $this->actionName = $action;
    }

    public function getActionName() {
        return $this->actionName;
    }

    public function setParams(array $params) {
        $this->params = $params;
    }

    public function addParam($key, $value) {
        $this->params[$key] = $value;
    }

    public function getParam($key) {
        if(!isset($this->params[$key])) {
            /** @TODO throw exception */
        }
        return $this->params[$key];
    }

    public function getParams() {
        return $this->params;
    }

    /**
     * @param string $controllerName
     * @param string|null $actionName
     * @param array $params
     * @return \Shift1\Core\Controller\Factory\ControllerAggregate
     */
    public function createController($controllerName, $actionName = null, array $params = array()) {
        $this->setControllerName($controllerName);
        $this->setActionName($actionName);
        $this->setParams($params);
        return $this->getController();
    }

}