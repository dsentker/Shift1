<?php
namespace Shift1\Core\Routing\Router;

use Shift1\Core\Request\RequestInterface;
use Shift1\Core\Routing\Route\RouteInterface;
use Shift1\Core\Routing\Route\Route;
use Shift1\Core\Routing\Exceptions\RouterException;
use Shift1\Core\Routing\PassChecker\PassCheckerInterface;
use Shift1\Core\Routing\Result\RoutingResult;

class Router {

    const KEY_VALUE_PAIR_EXPRESSION = '([^:/]*):("[^"]*"|[^/"]*)'; // Thanks to stackoverflow.com/questions/168171/regular-expression-for-parsing-name-value-pairs


    protected $request;

    /**
     * @var RoutingResult
     */
    protected $routingResult;

    /**
     * @param RequestInterface $request
     * @param RoutingResult $routingResult
     */
    public function __construct(RequestInterface $request, RoutingResult $routingResult) {
        $this->request = $request;
        $this->routingResult = $routingResult;
    }

    /**
     * @return RoutingResult
     */
    public function getRoutingResult() {
        return $this->routingResult;
    }

    public function getRequest() {
        return $this->request;
    }

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
     * @return RoutingResult
     * @throws \Shift1\Core\Routing\Exceptions\RouterException
     */
    public function getRequestData() {

        $result = $this->getRoutingResult();
        $uri = $this->getRequest()->getAppRequest();

        foreach($this->getRoutes() as $route) {
            /** @var $route RouteInterface */
            $routeExpression = $route->getSchemeExpression();

            if(1 === \preg_match_all($routeExpression, $uri, $matches)) {
                \array_shift($matches);
                $this->fetchData($route, $matches);
                $this->fetchRequestParams($uri);

                $passCheckerNs = $route->getPassCheckerNamespace();
                if(!empty($passCheckerNs)) {
                    $passChecker = new $passCheckerNs;
                    if(!($passChecker instanceof PassCheckerInterface)) {
                        throw new RouterException("Given Passchecker for route '{$route->getName()}' must be an instance of PassCheckerInterface!", RouterException::PASSCHECKER_INVALID);
                    }
                    /** @var $passChecker PassCheckerInterface */
                    if(!$passChecker->isValid($result)) {
                        continue; // Did not pass; try next route
                    }
                }

                $route->replaceHandlerBindings($result);
                $this->getRoutingResult()->setRoute($route);
                return $result;
            }

        }
        return new RoutingResult();
    }

    /**
     * @param RoutingResult $result
     * @param string $uri
     * @return int
     */
    protected function fetchRequestParams($uri) {

        if($this->getRequest()->isCli()) {
            $data = $this->getRequest()->parseCliArgs();
        } else {
            $data = array();
            // optional key-value-pairs
            \preg_match_all('#' . self::KEY_VALUE_PAIR_EXPRESSION . '#', $uri, $keyValuePairs);
            if(!empty($keyValuePairs[2])) {
                foreach($keyValuePairs[1] as $key => $val) {
                    $paramKey = $keyValuePairs[1][$key];
                    $paramVal = $keyValuePairs[2][$key];
                    $data[$paramKey] = $paramVal;
                }
            }
        }

        $this->getRoutingResult()->mergeArray($data);
        return \count($data);
    }

    /**
     * @param RouteInterface $route
     * @param array $uriParams
     * @return int
     */
    protected function fetchData(RouteInterface $route, array $uriParams) {

        $data = array();

        // defined uri param bindings
        foreach($uriParams as $position =>  $uriParam)  {
            $uriParam = $uriParam[0];
            $paramName = $route->getParamNameByPosition($position);
            $data[$paramName] = $uriParam;
        }

        $this->getRoutingResult()->mergeArray($data);
        return \count($data);

    }

    public static function fromConfig(array $routes, RequestInterface $request, RoutingResult $routingResult) {
        $router = new self($request, $routingResult);
        foreach($routes as $routeName => $routeData)  {

            if(!isset($routeData['handler'])) {
                throw new RouterException("No route handler defined for '{$routeName}'!", RouterException::ROUTE_HANDLER_MISSING);
            }

            $route = new Route($routeName, $routeData['request']);
            $route->setHandler($routeData['handler']);
            $route->setParamOptions(isset($routeData['bindings']) ? $routeData['bindings'] : array());
            $router->addRoute($route);
        }
        return $router;
    }


}
