<?php
namespace Shift1\Core\View\ControllerViewReloader;

use Shift1\Core\Controller\Factory\ControllerFactoryInterface;
use Shift1\Core\View\ViewInterface;
use Shift1\Core\Exceptions\ControllerViewReloaderException;

class ControllerViewReloader {

    /**
     * @var \Shift1\Core\Controller\Factory\ControllerFactoryInterface
     */
    protected $controllerFactory;

    public function __construct(ControllerFactoryInterface $controllerFactory) {
        $this->controllerFactory = $controllerFactory;
    }


    /**
     * @param string $path
     * @return \Shift1\Core\View\ViewInterface
     */
    public function loadByTemplateLocation($path) {
        $pathParts = \explode('/', $path);
        $controller = $pathParts[0];
        $action = (isset($pathParts[1])) ? $pathParts[1] : null;
        return $this->reloadView($controller, $action);
    }


    /**
     * @param string $definition
     * @return \Shift1\Core\View\ViewInterface
     */
    public function loadByControllerDefinition($definition) {
        $controllerParts = \explode('::', $definition);
        $controller = $controllerParts[0];
        $action = (isset($controllerParts[1])) ? $controllerParts[1] : null;
        return $this->reloadView($controller, $action);
    }

    protected function reloadView($controller, $action = null) {
        $view = $this->controllerFactory->createController($controller, $action)->run()->getContent();
        if(!($view instanceof ViewInterface)) {
            throw new ControllerViewReloaderException("Action {$controller}::{$action} must return a Instance of ViewInterface to reload view!");
        }
        return $view;

    }

}
