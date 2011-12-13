<?php

namespace Shift1\Core\Router;

interface iRouter {

    /**
     * @abstract
     * @param string $uri
     * @return array
     */
    public function resolveUri($uri);

    /**
     * @abstract
     * @param string $identifier
     * @param Route\iRoute $route
     * @return void
     */
    public function addRoute($identifier, Route\iRoute $route);

}