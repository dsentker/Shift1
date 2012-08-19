<?php
namespace Shift1\Core\Controller\Factory;

use Shift1\Core\Bundle\Definition\ActionResolver;

interface ControllerFactoryInterface {

    /**
     * @abstract
     * @param \Shift1\Core\Bundle\Definition\ActionResolver $actionDefinition
     * @param array $params
     * @return void
     */
    function createController(ActionResolver $actionDefinition, array $params = array());

}