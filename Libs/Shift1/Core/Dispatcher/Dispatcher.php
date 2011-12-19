<?php
namespace Shift1\Core\Dispatcher;

use Shift1\Core\Exceptions\DispatcherException;
use Shift1\Core\Controller\AbstractController;
use Shift1\Core\Router\iRouter;

use Shift1\Core\Controller\Factory\ControllerFactory;

class Dispatcher extends AbstractDispatcher {

    const CONTROLLER_SUFFIX = 'Controller';

    const ACTION_SUFFIX = 'Action';

    /**
     * @var array
     */
    protected $uriParts = array();

    /**
     * @param mixed $result
     * @return bool
     */
    protected function validateRequestResult($result) {
        if($result === false) {
            return false;
        }
        return true;
    }

    /**
     * @throws \Shift1\Core\Exceptions\DispatcherException
     * @return \Shift1\Core\Controller\AbstractController
     */
    public function dispatch() {

        $data = $this->getRequest()->assembleController();
        $config = $this->getApp()->getConfig();

        if($this->validateRequestResult($data) === false) {
            throw new DispatcherException('No valid Request result given: ' . \var_export($data, 1));
        }

        return ControllerFactory::createController($data['_controller'], $data['_action'], $data['_params']);


    }

    /**
     * @param string $controller
     * @param string $action
     * @return bool
     */
    protected function actionExists($controller, $action) {
        return \is_callable(array($controller, $action));
    }

    /**
     * @param string $controllerClass
     * @return bool
     */
    protected function controllerExists($controllerClass) {
        return \class_exists($controllerClass);
    }
    
}