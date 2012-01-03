<?php

namespace Shift1\Core\Router;

use Shift1\Core\Config\File\ConfigFileInterface;

class Router extends AbstractRouter {

    /**
     * @static
     * @param \Shift1\Core\Config\File\ConfigFileInterface $file
     * @return Router
     */
    public static function fromConfig(ConfigFileInterface $file) {
        $routes = $file->toArray();
        $router = new self();
        foreach($routes as $name => $route) {
            $route = new Route\Route($route['scheme'], $route['bindings']);
            $router->addRoute($name, $route);
        }
        return $router;

    }

}