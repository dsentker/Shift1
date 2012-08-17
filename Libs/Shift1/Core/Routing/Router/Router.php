<?php
namespace Shift1\Core\Routing\Router;

use Shift1\Core\Request\RequestInterface;
use Shift1\Core\Routing\Route\RouteInterface;
use Shift1\Core\Routing\Route\Route;
use Shift1\Core\Routing\Exceptions\RouterException;
use Shift1\Core\Routing\PassChecker\PassCheckerInterface;


class Router {

    const KEY_VALUE_PAIR_EXPRESSION = '([^:/]*):("[^"]*"|[^/"]*)'; // Thanks to stackoverflow.com/questions/168171/regular-expression-for-parsing-name-value-pairs

    /**
     * @var array
     */
    protected $routes = array();

    public function addRoute(RouteInterface $route) {
        $this->routes[$route->getName()] = $route;
    }

    /**
     * @param $routeName
     * @return RouteInterface
     * @throws \Shift1\Core\Routing\Exceptions\RouterException
     */
    public function getRoute($routeName) {
        if(!isset($this->routes[$routeName])) {
            throw new RouterException("Route '{$routeName}' not found!", RouterException::ROUTE_NOT_FOUND);
        }
        return $this->routes[$routeName];
    }

    /**
     * @return array
     */
    public function getRoutes() {
        return $this->routes;
    }

    /**
     * @param string $uri
     * @return array
     * @throws \Shift1\Core\Routing\Exceptions\RouterException
     */
    public function getDataFromUri($uri) {

        foreach($this->getRoutes() as $route) {
            /** @var $route RouteInterface */
            $routeExpression = $route->getSchemeExpression();

            if(1 === \preg_match_all($routeExpression, $uri, $matches)) {
                \array_shift($matches);
                $data = $this->fetchData($route, $matches, $uri);

                $passCheckerNs = $route->getPassCheckerNamespace();
                if(!empty($passCheckerNs)) {
                    $passChecker = new $passCheckerNs;
                    if(!($passChecker instanceof PassCheckerInterface)) {
                        throw new RouterException("Given Passchecker for route '{$route->getName()}' must be an instance of PassCheckerInterface!", RouterException::PASSCHECKER_INVALID);
                    }
                    /** @var $passChecker PassCheckerInterface */
                    if(!$passChecker->isValid($data)) {
                        continue; // Did not pass; try next route
                    }
                }

                $route->replaceHandlerBindings($data);
                $data['_route'] = $route;
                return $data;
            }

        }
        return array();
    }

    /**
     * @param RouteInterface $route
     * @param array $uriParams
     * @param string $uri
     * @return array
     */
    protected function fetchData(RouteInterface $route, array $uriParams, $uri = '') {

        $data = array();

        // defined uri param bindings
        foreach($uriParams as $position =>  $uriParam)  {
            $uriParam = $uriParam[0];
            $paramName = $route->getParamNameByPosition($position);
            $data[$paramName] = $uriParam;
        }

        // optional key-value-pairs
        \preg_match_all('#' . self::KEY_VALUE_PAIR_EXPRESSION . '#', $uri, $keyValuePairs);
        if(!empty($keyValuePairs[2])) {
            foreach($keyValuePairs[1] as $key => $val) {
                $paramKey = $keyValuePairs[1][$key];
                $paramVal = $keyValuePairs[2][$key];
                $data[$paramKey] = $paramVal;
            }
        }

        return $data;

    }

    public static function fromConfig(array $routes) {
        $router = new self();
        foreach($routes as $routeName => $routeData)  {

            if(!isset($routeData['handler'])) {
                throw new RouterException("No route handler defined for '{$routeName}'!", RouterException::ROUTE_HANDLER_MISSING);
            }

            $route = new Route($routeName, $routeData['route']);
            $route->setHandler($routeData['handler']);
            $route->setParamOptions(isset($routeData['bindings']) ? $routeData['bindings'] : array());
            $router->addRoute($route);
        }
        return $router;
    }


}