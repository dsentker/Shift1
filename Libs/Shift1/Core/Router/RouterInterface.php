<?php

namespace Shift1\Core\Router;

use Shift1\Core\Router\Route\RouteInterface;

interface RouterInterface {

    /**
     * @abstract
     * @param string $uri
     * @return array
     */
    public function resolveUri($uri);

    /**
     * @abstract
     * @param string $identifier
     * @param RouteInterface $route
     * @return void
     */
    function addRoute($identifier, RouteInterface $route);

}