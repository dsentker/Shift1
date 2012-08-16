<?php
namespace Shift1\Core\Router;

use Shift1\Core\Router\Route\RouteInterface;
use Shift1\Core\Exceptions\RouteException;
use Shift1\Core\Request\RequestInterface;

abstract class AbstractRouter implements RouterInterface {

    /**
     * @var array
     */
    protected $routes = array();



    /**
     * @return array
     */
    public function getRoutes() {
        return $this->routes;
    }

    /**
     * @throws \Shift1\Core\Exceptions\RouteException
     * @param $identifier
     * @return Route\RouteInterface The matched Route object
     */
    public function getRoute($identifier) {
        if(!$this->hasRoute($identifier)) {
            throw new RouteException("Unable to find Route {$identifier}");
        }
        return $this->routes[$identifier];
    }

    /**
     * @param $identifier
     * @return bool
     */
    public function hasRoute($identifier) {
        return \array_key_exists($identifier, $this->getRoutes());
    }

    /**
     * @param $identifier
     * @param Route\RouteInterface $route
     * @return void
     */
    public function addRoute($identifier, RouteInterface $route) {
        $this->routes[$identifier] = $route;
    }

    /**
     * @throws \Shift1\Core\Exceptions\RouteException
     * @param string $identifier
     * @return void
     */
    public function removeRoute($identifier) {
        if(!$this->hasRoute($identifier)) {
            throw new RouteException("Unable to remove Route {$identifier} - Route not found.");
        }
        unset($this->routes[$identifier]);
    }


}