<?php
namespace Shift1\Core\Controller\Factory;

use Shift1\Core\Response\ResponseInterface;
use Shift1\Core\FrontController;
use Shift1\Core\Service\ContainerAccess;
use Shift1\Core\Service\Container\ServiceContainerInterface;
use Shift1\Core\Bundle\Definition\ControllerDefinition;
use Shift1\Core\Bundle\Definition\ActionDefinition;

class ControllerFactory implements ControllerFactoryInterface, ContainerAccess {

    /**
     * @var array
     */
    protected $params = array();

    /**
     * @var string
     */
    protected $bundleDefinition;

    /**
     * @var string
     */
    protected $controllerName;

    /**
     * @var string
     */
    protected $actionName;

    /**
     * @var ServiceContainerInterface
     */
    protected $container;

    public function __construct() {

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

        $controllerDefinitionString = \sprintf('%s:%s', $this->getBundleDefinition(), $this->getControllerName());
        $controllerDefinition = new ControllerDefinition($controllerDefinitionString);
        $controllerNamespace = $controllerDefinition->getNamespace();

        if(!\class_exists($controllerNamespace)) {
            /** @TODO : Throw exception here */
            die('CONTROLLER NOT FOUND :' . $controllerNamespace );
        }

        return $this->getControllerInstance($controllerDefinition, $this->getActionName(), $this->getParams());

    }

    /**
     * @param ControllerDefinition $controllerDefinition
     * @param string $actionName
     * @param array $params
     * @internal param string $controllerClass
     * @return \Shift1\Core\Controller\Factory\ControllerAggregate
     */
    protected function getControllerInstance(ControllerDefinition $controllerDefinition, $actionName, array $params = array()) {

        $rfController = new \ReflectionClass($controllerDefinition->getNamespace());

        /** @var $controller \Shift1\Core\Controller\AbstractController */
        $controller = $rfController->newInstanceArgs(array($params));

        $actionDefinitionString = $controllerDefinition->getControllerDefinition() . '::' . $actionName;
        $actionDefinition = new ActionDefinition($actionDefinitionString);
        $actionName = $actionDefinition->getActionName();

        if(!\method_exists($controller, $actionName)) {
            $actionName = $controller::getNotFoundActionName();
            $params['_requestedDefinition'] = $actionDefinition;
            // Recursive call
            return $this->getControllerInstance($controllerDefinition, $actionName, $params);
        }

        $controller->addParam('_dispatchedDefinition', $actionDefinition);
        $controller->setContainer($this->getContainer());
        $actionParams = $this->mapParamsToActionArgs($params, new \ReflectionMethod($controller, $actionName));
        return new ControllerAggregate($controller, $actionName, $actionParams);
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

    public function setBundleDefinition($bundle) {
        $this->bundleDefinition = $bundle;
    }

    public function getBundleDefinition() {
        return $this->bundleDefinition;
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
     * @param string $bundleDefinition e.g. shift1:foo
     * @param string $controllerName
     * @param string|null $actionName
     * @param array $params
     * @return \Shift1\Core\Controller\Factory\ControllerAggregate
     */
    public function createController($bundleDefinition, $controllerName, $actionName = null, array $params = array()) {
        $this->setBundleDefinition($bundleDefinition);
        $this->setControllerName($controllerName);
        $this->setActionName($actionName);
        $this->setParams($params);
        return $this->getController();
    }

}