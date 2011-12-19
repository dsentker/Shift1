<?php
namespace Shift1\Core\Controller\Factory;

class ControllerFactory extends AbstractControllerFactory {

    /**
     * @static
     * @param string $controllerName
     * @param null|string $actionName
     * @param array $params
     * @return \Shift1\Core\Controller\AbstractController
     */
    public static function createController($controllerName, $actionName = null, array $params = array()) {
        $factory = new self;
        $factory->setControllerName($controllerName);
        $factory->setActionName($actionName);
        $factory->setParams($params);
        return $factory->getController();
    }

}