<?php

namespace Shift1\Core\Router;

use Shift1\Core\Router\Route\RouteInterface;

interface RouterInterface {

    /**
     * @abstract
     * @return array
     */
    public function resolve();

    /**
     * @abstract
     * @param $identifier
     * @param Route\RouteInterface $route
     * @return void
     */
    function addRoute($identifier, RouteInterface $route);

}