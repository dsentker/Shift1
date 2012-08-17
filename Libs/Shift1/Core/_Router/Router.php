<?php

namespace Shift1\Core\Router;

use Shift1\Core\Config\File\ConfigFileInterface;
use Shift1\Core\Request\RequestInterface;
use Shift1\Core\Router\ParamConverter\Factory\ParamConverterFactory;
use Shift1\Core\Exceptions\RouteException;
use Shift1\Core\Router\Route\RouteInterface;

class Router extends AbstractRouter {

    /**
     * @var \Shift1\Core\Router\ParamConverter\Factory\ParamConverterFactory
     */
    protected $paramConverterFactory;

    /**
     * @var \Shift1\Core\Request\RequestInterface
     */
    protected $request;

    /**
     * @param \Shift1\Core\Request\RequestInterface $request
     * @param \Shift1\Core\Router\ParamConverter\Factory\ParamConverterFactory $converterFactory
     */
    public function __construct(RequestInterface $request, ParamConverterFactory $converterFactory) {
        $this->request = $request;
        $this->paramConverterFactory = $converterFactory;
    }

    /**
     * @return ParamConverter\Factory\ParamConverterFactory
     */
    public function getParamConverterFactory() {
        return $this->paramConverterFactory;
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
            $converterFactory   = $this->getParamConverterFactory();

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
     * @throws \Shift1\Core\Exceptions\RouteException
     * @param string $compareWithUri
     * @return string The name of the route
     */
    public function getMatchingRoute($compareWithUri) {
        foreach($this->getRoutes() as $routeName => $route) {
            /** @var $route Route\RouteInterface */

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
        $converterFactory   = $this->getParamConverterFactory();

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



    /**
     * @static
     * @param \Shift1\Core\Request\RequestInterface $request
     * @param \Shift1\Core\Config\File\ConfigFileInterface $file
     * @param \Shift1\Core\Router\ParamConverter\Factory\ParamConverterFactory $converterFactory
     * @return Router
     */
    public static function fromConfig(RequestInterface $request, ConfigFileInterface $file, ParamConverterFactory $converterFactory) {
        $routes = $file->toArray();
        $router = new self($request, $converterFactory);
        foreach($routes as $name => $route) {
            $route = new Route\Route($route['scheme'], $route['bindings']);
            $router->addRoute($name, $route);
        }
        return $router;

    }

}