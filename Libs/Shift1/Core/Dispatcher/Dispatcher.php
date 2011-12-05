<?php

namespace Shift1\Core\Dispatcher;

use Shift1\Core\Exceptions\DispatcherException;
use Shift1\Core\Router\iRouter;


class Dispatcher extends AbstractDispatcher {

    const CONTROLLER_SUFFIX = 'Controller';
    const ACTION_SUFFIX = 'Action';

    protected $routeResult;

    protected $uriParts = array();

    protected function validateRequestResult($result) {
        if($result === false) {
            return false;
        }
        return true;
    }

    public function dispatch() {

        $data = $this->getRequest()->assembleController();
        $config = $this->getApp()->getConfig();

        if($this->validateRequestResult($data) === false) {
            throw new DispatcherException('No valid Request result given: ' . \var_export($data, 1));
        }

        $controllerClassName =  (!empty($data['_controller']))
                ? $data['_controller'] . self::CONTROLLER_SUFFIX
                : null;
        $actionMethodName =     (!empty($data['_action']))
                ? $data['_action'] . self::ACTION_SUFFIX
                : null;

        $controllerNamespace = $config->controller->namespace;
        $controllerClass = $controllerNamespace . $controllerClassName;
        $named_params['_controllerNamespace'] = $controllerNamespace;
        
        if(empty($controllerClassName)) {
            // No Controller was given
            $controllerClassName = $config->controller->defaultController . self::CONTROLLER_SUFFIX;
            $controllerClass = $controllerNamespace . $controllerClassName;
            $actionMethodName = $controllerClass::$actionNotFound . self::ACTION_SUFFIX;
        } elseif(!$this->controllerExists($controllerClass)) {
            // Controller was defined, but does not exist
            $controllerClassName = $config->controller->errorController . self::CONTROLLER_SUFFIX;
            $controllerClass = $controllerNamespace . $controllerClassName;
            $actionMethodName = $controllerClass::$actionDefault . self::ACTION_SUFFIX;
        } else {
            // Requested Controller exists!
            if(empty($actionMethodName)) {
                $actionMethodName = $controllerClass::$actionDefault . self::ACTION_SUFFIX;
            } elseif(!$this->actionExists($controllerClass, $actionMethodName)) {
                $actionMethodName = $controllerClass::$actionNotFound . self::ACTION_SUFFIX;
            }
        }

        return array(
            'controllerClass' => $controllerClass,
            'actionMethod'    => $actionMethodName,
            'params'          => $data,
        );

    }

    protected function actionExists($controller, $action) {
        return \is_callable(array($controller, $action));
    }

    protected function controllerExists($controllerClass) {
        return \class_exists($controllerClass);
    }
    

}

?>