<?php
namespace Shift1\Core\View\ControllerViewReloader;

use Shift1\Core\Controller\Factory\ControllerFactoryInterface;
use Shift1\Core\Bundle\Definition\ActionDefinition;
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
     * @param \Shift1\Core\InternalFilePath $path
     * @return \Shift1\Core\View\ViewInterface|string
     */
    public function loadByTemplateLocation(InternalFilePath $path) {
        /** @TODO NOT WORKING ATM */
        $pathParts = $path->getAbsolutePathAsArray();
        $file = \array_pop($pathParts);
        $fileSplit = \explode('.', $file, 2);
        $action = $fileSplit[0];
        $controller = \array_pop($pathParts);

        return $this->reloadView($controller, $action);
    }


    /**
     * @param \Shift1\Core\Bundle\Definition\ActionDefinition $definition
     * @return \Shift1\Core\View\ViewInterface
     */
    public function loadByActionDefinition(ActionDefinition $definition) {

        $bundleName     = $definition->getBundleName();
        $controllerName = $definition->getControllerName();
        $actionName     = $definition->getActionName();

        return $this->reloadView($bundleName, $controllerName, $actionName);
    }

    protected function reloadView($bundleName, $controller, $action = null) {
        $view = $this->controllerFactory->createController($bundleName, $controller, $action)->run()->getContent();
        if(!($view instanceof ViewInterface)) {
            throw new ControllerViewReloaderException("Action {$controller}::{$action} must return a Instance of ViewInterface to reload view!");
        }
        return $view;

    }

}
