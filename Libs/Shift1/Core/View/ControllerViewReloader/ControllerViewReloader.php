<?php
namespace Shift1\Core\View\ControllerViewReloader;

use Shift1\Core\Controller\Factory\ControllerFactoryInterface;
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
        $pathParts = $path->getAbsolutePathAsArray();
        $file = \array_pop($pathParts);
        $fileSplit = \explode('.', $file, 2);
        $action = $fileSplit[0];
        $controller = \array_pop($pathParts);

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
