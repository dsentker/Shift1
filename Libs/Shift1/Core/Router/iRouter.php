<?php

namespace Shift1\Core\Router;

interface iRouter {

    public function resolveUri($uri);

    public function addRoute($identifier, Route\iRoute $route);

}