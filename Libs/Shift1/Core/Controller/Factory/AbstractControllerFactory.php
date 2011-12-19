<?php
namespace Shift1\Core\Controller\Factory;

use Shift1\Core\Shift1Object;
use Shift1\Core\Response\iResponse;

class AbstractControllerFactory extends Shift1Object implements iControllerFactory {

    /**
     * @var \Shift1\Core\Config\Manager\iConfigManager
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

    public function __construct() {
        $this->config = $this->getApp()->getConfig();
    }

    /**
     * @return \Shift1\Core\Response\iResponse
     */
    public function getController() {

        $controllerNamespace = $this->config->controller->namespace;
        $controllerName = \ucfirst($this->getControllerName());
        $controllerFqNs = $controllerNamespace . $controllerName . self::CONTROLLER_PREFIX;
        
        if(empty($controllerName)) {
            $controllerName = $this->config->controller->defaultController;

            /** @var $controllerFqNs \Shift1\Core\Controller\AbstractController */
            $controllerFqNs = $controllerNamespace . $controllerName . self::CONTROLLER_PREFIX;

            return $this->getControllerInstance($controllerFqNs, $controllerFqNs::getDefaultActionName());
        }

        if(!\class_exists($controllerFqNs)) {
            $controllerName = $this->config->controller->errorController;
            $controllerFqNs = $controllerNamespace . $controllerName . self::CONTROLLER_PREFIX;
            return $this->getControllerInstance($controllerFqNs, $controllerFqNs::getDefaultActionName());
        }

        return $this->getControllerInstance($controllerFqNs, $this->getActionName(), $this->getParams());

    }

    /**
     * @param string $controllerFqNs
     * @param string $actionName
     * @param array $params
     * @return mixed
     */
    protected function getControllerInstance($controllerFqNs, $actionName, array $params = array()) {

        $actionName = $actionName . self::ACTION_PREFIX;

        $rfController = new \ReflectionClass($controllerFqNs);

        /** @var $controller \Shift1\Core\Controller\AbstractController */
        $controller = $rfController->newInstanceArgs(array($params));

        if(! \method_exists($controller, $actionName)) {
            $actionName = $controller::getNotFoundActionName() . self::ACTION_PREFIX;
        }

        $actionParams = $this->mapParamsToActionArgs($params, new \ReflectionMethod($controller, $actionName));

        return \call_user_func_array(array($controller, $actionName), $actionParams);
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

            if(isset($uriParams[$param->getName()])) {
                $actionParams[$paramPosition] = $uriParams[$param->getName()];
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

}
