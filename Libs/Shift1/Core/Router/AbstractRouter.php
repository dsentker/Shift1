<?php
namespace Shift1\Core\Router;

use Shift1\Core\Shift1Object;
use Shift1\Core\Router\Route\iRoute;
use Shift1\Core\Exceptions\RouteException;

abstract class AbstractRouter extends Shift1Object implements iRouter {

    /**
     * @var array
     */
    protected $routes = array();

    /**
     * 
     */
    public function __construct() {

    }

    /**
     * @param string $requestUri
     * @param string $routeName
     * @return array
     */
    protected function fetchParams($requestUri, $routeName) {

        $route = $this->getRoute($routeName);

        $fetched = array();

        // Fill in default values
        foreach($route->getBindings() as $bindingName => $bindingOpts) {
            if(isset($bindingOpts['default'])) {
                $fetched[$bindingName] = $bindingOpts['default'];
            }
        }

        if(!empty($requestUri)) {

            $requestUriParts = \explode(iRoute::URI_SEGMENT_SEPARATOR, \trim($requestUri,'/'));
            $routeParts =      \explode(iRoute::URI_SEGMENT_SEPARATOR, \trim($route->getScheme(), iRoute::URI_SEGMENT_SEPARATOR));

            foreach($requestUriParts as $position => $segment) {

                if(isset($routeParts[$position])) {
                    if($route->isBindedSegment($routeParts[$position])) {
                        $routeKey = \str_replace(iRoute::KEYBINDING_CHAR, '', $routeParts[$position]);
                        $fetched[$routeKey] = $this->transformParamValue($segment);
                    } else {
                        // This segment is just a text value without special purpose. ignore.
                    }
                } else {
                    if($keyValue = $this->getParamFromSegment($segment)) {
                        $fetched[$keyValue['key']] = $this->transformParamValue($keyValue['value']);
                    }
                }
                
            }
        }

        $fetched['_routeScheme'] = $route->getScheme();
        $fetched['_routeName'] = $routeName;
        return $fetched;

    }

    /**
     * @param string $param
     * @return bool
     * @TODO swap out this functionality
     */
    protected function transformParamValue($param) {
        if($param === '1' || $param == 'true') {
            $value = true;
        } elseif($param === '0' || $param == 'false') {
            $value = false;
        } else {
            $value = $param;
        }
        return $value;
    }

    /**
     * Returns the key and value from given segment.
     * Returns FALSE if this segment is not an parameter binding.
     * @param string $segment
     * @return array|bool
     */
    protected function getParamFromSegment($segment) {
        if(\strpos($segment, iRoute::URI_PARAM_KEY_SEPARATOR) !== false ) {
            $parts = \explode(iRoute::URI_PARAM_KEY_SEPARATOR, $segment);
            return array(
              'key' => \array_shift($parts),
              'value' => \implode(iRoute::URI_PARAM_KEY_SEPARATOR, $parts),
            );
        } else {
            return false;
        }
    }

    /**
     * @param string $requestUri
     * @return array|bool
     */
    public function resolveUri($requestUri) {

        $routeName = $this->getMatchingRoute($requestUri);
        return $this->fetchParams($requestUri, $routeName);

    }

    /**
     * @return array
     */
    public function getRoutes() {
        return $this->routes;
    }

    /**
     * @throws \Shift1\Core\Exceptions\RouteException
     * @param $identifier
     * @return Route\iRoute
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
     * @param string $identifier
     * @param Route\iRoute $route
     * @return void
     */
    public function addRoute($identifier, Route\iRoute $route) {
        $this->routes[$identifier] = $route;
    }

    /**
     * @throws \Shift1\Core\Exceptions\RouteException
     * @param string $identifier
     * @return void
     */
    public function removeRoute($identifier) {
        if(!$this->hasRoute($identifier)) {
            throw new RouteException("Unable to remove Route {$identifier} - not found.");
        }
        unset($this->routes[$identifier]);
    }

    /**
     * @throws \Shift1\Core\Exceptions\RouteException
     * @param string $compareWithUri
     * @return string The name of the route
     */
    public function getMatchingRoute($compareWithUri) {
        foreach($this->getRoutes() as $routeName => $route) {
            /** @var $route Route\iRoute */
            #var_dump($route->getSchemeAsPattern());
            if(\preg_match('#' . $route->getSchemeAsPattern() . '#', $compareWithUri)) {
                return $routeName;
            }
        }
        throw new RouteException("Unabble to find a matching route for {$compareWithUri}");
    }
}