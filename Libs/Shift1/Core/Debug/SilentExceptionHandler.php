<?php
namespace Shift1\Core\Debug;

/**
 * @TODO code me again
 */

use Shift1\Core\View\View;
use Shift1\Core\Response;
use Shift1\Core\Dispatcher\Dispatcher;

class SilentExceptionHandler extends AbstractExceptionHandler {

    const FETCH_LINES_BEFORE = 6;

    const FETCH_LINES_AFTER = 3;

    public function handle(\Exception $e) {

        $config = $this->getApp()->getConfig();

        $errorControllerName = $config->controller->errorController;
        $errorControllerClass = $errorControllerName . Dispatcher::CONTROLLER_SUFFIX;
        $controllerNamespace = $config->controller->namespace;
        $controllerClass = $controllerNamespace . $errorControllerClass;
        $actionName = $controllerClass::$actionDefault;
        $action = $actionName . Dispatcher::ACTION_SUFFIX;

        $params = array(
            '_controller' => $errorControllerName,
            '_action'     => $actionName,
        );

        $rfController= new \ReflectionClass($controllerClass);
        $controller = $rfController->newInstanceArgs(array($params));
        $response = \call_user_func(array($controller, $action));
        $response->sendToClient();

        return false;
    }
}