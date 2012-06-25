<?php
namespace Shift1\Core\Router;

use Shift1\Core\Router\Route\RouteInterface;
use Shift1\Core\Exceptions\RouteException;
use Shift1\Core\Request\RequestInterface;
use Shift1\Core\Service\ContainerAccess;
use Shift1\Core\Service\Container\ServiceContainerInterface;


abstract class AbstractRouter implements RouterInterface, ContainerAccess {

    /**
     * @var array
     */
    protected $routes = array();

    /**
     * @var \Shift1\Core\Request\RequestInterface
     */
    protected $request;

    protected $container;

    /**
     * @param \Shift1\Core\Request\RequestInterface $request
     */
    public function __construct(RequestInterface $request) {
        $this->request = $request;
    }

    public function setContainer(ServiceContainerInterface $container) {
        $this->container = $container;
    }

    public function getContainer() {
        return $this->container;
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

            $requestUriParts    = \explode(RouteInterface::URI_SEGMENT_SEPARATOR, \trim($requestUri,'/'));
            $routeParts         = \explode(RouteInterface::URI_SEGMENT_SEPARATOR, \trim($route->getScheme(), RouteInterface::URI_SEGMENT_SEPARATOR));
            $converterFactory   = $this->getContainer()->get('shift1.paramConverterFactory');
            /** @var $converterFactory \Shift1\Core\Router\ParamConverter\Factory\ParamConverterFactory */

            foreach($requestUriParts as $position => $segment) {
                /** @var $converter \Shift1\Core\Router\ParamConverter\AbstractParamConverter */

                if(isset($routeParts[$position])) {

                    // check if current route segment is an binding like @slug
                    if($route->isBindedSegment($routeParts[$position])) {
                        $routeKey = \str_replace(RouteInterface::KEYBINDING_CHAR, '', $routeParts[$position]);
                        $bindingOpts = $route->getBinding($routeKey);
                        $converterName = (isset($bindingOpts['converter'])) ? $bindingOpts['converter'] : null;
                        $converter = $converterFactory->createConverter($converterName);
                        $fetched[$routeKey] = $converter->getActionParam($segment);

                    } else {
                        // This segment is just a string without special purpose. ignore.
                    }
                } else {

                    // check if there is something like param:value in segment
                    if($keyValue = $this->getParamFromSegment($segment)) {
                        $converter = $converterFactory->createConverter();
                        $fetched[$keyValue['key']] = $converter->getActionParam($keyValue['value']);
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
     * Returns the key and value from given segment.
     * Returns <i>false</i> if this segment is not a binded parameter.
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
     * @return array|bool
     */
    public function resolve() {

        $requestUri = $this->request->getAppRequestUri();

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
            /** @var $route Route\RouteInterface */
            #var_dump($route->getSchemeAsPattern());
            if(\preg_match('#' . $route->getSchemeAsPattern() . '#', $compareWithUri)) {
                return $routeName;
            }
        }
        throw new RouteException("Unable to find a matching route for {$compareWithUri}");
    }

    public function getAnchor(array $params, $routeName = 'default') {
        if(!$this->hasRoute($routeName)) {
            throw new RouteException("Could not build an anchor: Route Name '{$routeName}' not found!");
        }

        $route = $this->getRoute($routeName);
        $routeSegments = $route->getSchemeSegments();

        $converterFactory   = $this->getContainer()->get('shift1.paramConverterFactory');
        /** @var $converterFactory \Shift1\Core\Router\ParamConverter\Factory\ParamConverterFactory */

        foreach($routeSegments as &$segment) {
            if($route->isBindedSegment($segment)) {

                $routeVariableName = \str_replace($route::KEYBINDING_CHAR, '', $segment);

                try {
                    $bindingOpts = $route->getBinding($routeVariableName);
                } catch(RouteException $e) {
                    // param without bindings - who cares?
                }

                $converterName = (isset($bindingOpts['converter'])) ? $bindingOpts['converter'] : null;
                $converter = $converterFactory->createConverter($converterName);
                /** @var $converter \Shift1\Core\Router\ParamConverter\AbstractParamConverter */

                if(\array_key_exists($routeVariableName, $params)) {
                    // param was given in $param array
                    $segment = $converter->getUriParam($params[$routeVariableName]);
                    unset($params[$routeVariableName]);
                } else {
                    // param missing
                    if(isset($bindingOpts['default'])) {
                        // missing param was given via routing config
                        $segment = $converter->getUriParam($bindingOpts['default']);
                    } else {
                        throw new RouteException("Could not build an anchor: No value for route param '{$segment}' given.");
                    }
                }
            } 
        }

        $appUri = \implode($route::URI_SEGMENT_SEPARATOR, $routeSegments);
        if(!empty($params)) {

            $appUri .= $route::URI_SEGMENT_SEPARATOR;
            foreach($params as $key => $val) {

                if($route->hasBinding($key)) {
                    $bindingOpts = $route->getBinding($key);
                    $converterName = (isset($bindingOpts['converter'])) ? $bindingOpts['converter'] : null;
                    $converter = $converterFactory->createConverter($converterName);
                    /** @var $converter \Shift1\Core\Router\ParamConverter\AbstractParamConverter */
                    $convertedVal = $converter->getUriParam($val);
                } else {
                    $converter = $converterFactory->createConverter();
                    /** @var $converter \Shift1\Core\Router\ParamConverter\AbstractParamConverter */
                    $convertedVal = $converter->getUriParam($val);
                }

                $appUri .= $key . $route::URI_PARAM_KEY_SEPARATOR . $convertedVal . $route::URI_SEGMENT_SEPARATOR;


            }
        }

        return '//' . $this->request->getAppRootUri() . \rtrim($appUri, $route::URI_SEGMENT_SEPARATOR) . $route::URI_SEGMENT_SEPARATOR;




    }
}