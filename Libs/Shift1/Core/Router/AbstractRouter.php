<?php
namespace Shift1\Core\Router;

use Shift1\Core\Router\Route\RouteInterface;
use Shift1\Core\Exceptions\RouteException;

abstract class AbstractRouter implements RouterInterface {

    /**
     * @var array
     */
    protected $routes = array();

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

            $requestUriParts = \explode(RouteInterface::URI_SEGMENT_SEPARATOR, \trim($requestUri,'/'));
            $routeParts =      \explode(RouteInterface::URI_SEGMENT_SEPARATOR, \trim($route->getScheme(), RouteInterface::URI_SEGMENT_SEPARATOR));

            foreach($requestUriParts as $position => $segment) {

                if(isset($routeParts[$position])) {
                    if($route->isBindedSegment($routeParts[$position])) {
                        $routeKey = \str_replace(RouteInterface::KEYBINDING_CHAR, '', $routeParts[$position]);
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

        $fetched['_route'] = array(
          'scheme'  =>  $route->getScheme(),
          'pattern' =>  $route->getSchemeAsPattern(),
          'name'    => $routeName,
        );
        
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
     * Returns FALSE if this segment is not a binded parameter.
     * @param string $segment
     * @return array|bool
     */
    protected function getParamFromSegment($segment) {
        if(!\strpos($segment, RouteInterface::URI_PARAM_KEY_SEPARATOR) !== false ) {
            return false;
        }

        $parts = \explode(RouteInterface::URI_PARAM_KEY_SEPARATOR, $segment);

        return array(
          'key' => \array_shift($parts),
          'value' => \implode(RouteInterface::URI_PARAM_KEY_SEPARATOR, $parts),
        );
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
     * @param RouteInterface $route
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