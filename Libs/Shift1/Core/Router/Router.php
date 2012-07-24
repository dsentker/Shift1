<?php

namespace Shift1\Core\Router;

use Shift1\Core\Config\File\ConfigFileInterface;
use Shift1\Core\Request\RequestInterface;
use Shift1\Core\Router\ParamConverter\Factory\ParamConverterFactory;

class Router extends AbstractRouter {

    /**
     * @static
     * @param \Shift1\Core\Config\File\ConfigFileInterface $file
     * @return Router
     */
    public static function fromConfig(RequestInterface $request, ConfigFileInterface $file, ParamConverterFactory $converterFactory) {
        $routes = $file->toArray();
        $router = new self($request, $converterFactory);
        foreach($routes as $name => $route) {
            $route = new Route\Route($route['scheme'], $route['bindings']);
            $router->addRoute($name, $route);
        }
        return $router;

    }

}