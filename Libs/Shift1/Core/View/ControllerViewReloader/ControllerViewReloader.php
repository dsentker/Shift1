<?php
namespace Shift1\Core\View\ControllerViewReloader;

use Shift1\Core\Controller\Factory\ControllerFactoryInterface;
use Shift1\Core\Bundle\Definition\ActionDefinition;
use Shift1\Core\Bundle\Definition\TemplateDefinition;
use Shift1\Core\View\ViewInterface;
use Shift1\Core\Exceptions\ControllerViewReloaderException;
use Shift1\Core\InternalFilePath;

class ControllerViewReloader {

    /**
     * @var \Shift1\Core\Controller\Factory\ControllerFactoryInterface
     */
    protected $controllerFactory;

    public function __construct(ControllerFactoryInterface $controllerFactory) {
        $this->controllerFactory = $controllerFactory;
    }

    /**
     * @param \Shift1\Core\Bundle\Definition\ActionDefinition $definition
     * @return \Shift1\Core\View\ViewInterface
     */
    public function loadByActionDefinition(ActionDefinition $definition) {

        $bundleDefinition   = $definition->getBundleDefinition();
        $controllerName     = $definition->getControllerName(false);
        $actionName         = $definition->getActionName(false);

        return $this->reloadView($bundleDefinition, $controllerName, $actionName);
    }

    protected function reloadView($bundleDefinition, $controller, $action = null) {
        $controllerResponse = $this->controllerFactory->createController($bundleDefinition, $controller, $action)->run();
        $view = $controllerResponse->getContent();
        if(!($view instanceof ViewInterface)) {
            throw new ControllerViewReloaderException("Action {$controller}::{$action} must return an Instance of ViewInterface to reload view!");
        }
        return $view;

    }

}
