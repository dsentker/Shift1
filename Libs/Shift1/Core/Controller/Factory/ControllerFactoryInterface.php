<?php
namespace Shift1\Core\Controller\Factory;
 
interface ControllerFactoryInterface {

    const CONTROLLER_SUFFIX = 'Controller';

    const ACTION_SUFFIX     = 'Action';

    /**
     * @param string $controllerName
     * @param string|null $actionName
     * @param array $params
     * @return \Shift1\Core\Controller\Factory\ControllerAggregate
     */
    function createController($controllerName, $actionName = null, array $params = array());

}