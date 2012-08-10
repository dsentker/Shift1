<?php
namespace Shift1\Core\Controller\Factory;
 
interface ControllerFactoryInterface {

    /**
     * @param string $bundleName
     * @param string $controllerName
     * @param string|null $actionName
     * @param array $params
     * @return \Shift1\Core\Controller\Factory\ControllerAggregate
     */
    function createController($bundleName, $controllerName, $actionName = null, array $params = array());

}