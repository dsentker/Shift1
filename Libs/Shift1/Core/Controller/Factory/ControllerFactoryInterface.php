<?php
namespace Shift1\Core\Controller\Factory;
 
interface ControllerFactoryInterface {

    /**
     * @param string $actionDefinition The action definition, e.g. vendor:bundleName:foo::bar
     * @param array $params
     * @return ControllerAggregate
     */
    function createController($actionDefinition, array $params = array());

}