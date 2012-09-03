<?php
namespace Shift1\Core\Routing\Route;

use Shift1\Core\Routing\Exceptions\RouteParamException;
use Shift1\Core\Routing\Exceptions\RouteException;
use Shift1\Core\Routing\Result\RoutingResult;

class Route implements RouteInterface {

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $handler;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var array
     */
    protected $schemeParamNames = array();

    /**
     * @var string
     */
    protected $passCheckerLocator = null;

    /**
     * @var array
     */
    protected $paramOptions = array();

    /**
     * @var int
     */
    protected $priority = 50;

    /**
     * @param string $name
     * @param string $scheme
     */
    public function __construct($name, $scheme) {
        $this->name = $name;
        $this->scheme = $scheme;
        \preg_match_all(self::PARAM_MATCH_EXPRESSION, $scheme, $schemeParamNames);
        $this->schemeParamNames = $schemeParamNames[1]; // 0 ist MIT den spitzen Klammern
    }

    public function getName() {
        return $this->name;
    }

    /**
     * @param string $handler
     */
    public function setHandler($handler) {
        $this->handler = $handler;
    }

    /**
     * @return string
     */
    public function getHandler() {
        return $this->handler;
    }

    /**
     * @param int $priority
     * @return void
     */
    public function setPriority($priority) {
        $this->priority = (int) $priority;
    }

    /**
     * @return int
     */
    public function getPriority() {
        return $this->priority;
    }

    /**
     * @return string
     */
    public function getScheme() {
        return $this->scheme;
    }

    /**
     * @param string $passCheckerLocator
     */
    public function setPassCheckerLocator($passCheckerLocator) {
        $this->passCheckerLocator = $passCheckerLocator;
    }

    /**
     * @return string
     */
    public function getPassCheckerLocator() {
        return $this->passCheckerLocator;
    }

    public function hasPassChecker() {
        return !empty($this->passCheckerLocator);
    }

    /**
     * @param int $position
     * @throws \Shift1\Core\Routing\Exceptions\RouteParamException
     * @return string
     */
    public function getParamNameByPosition($position) {
        if(!isset($this->schemeParamNames[$position]))  {
            throw new RouteParamException("No param set at position '{$position}' for route '{$this->getName()}'!", RouteParamException::PARAM_POSITION_NOT_FOUND);
        }
        return $this->schemeParamNames[$position];
    }

    /**
     * @return array
     */
    public function getParamNames()  {
        return $this->schemeParamNames;
    }

    /**
     * @param null|string $param
     * @return array
     * @throws \Shift1\Core\Routing\Exceptions\RouteParamException
     */
    public function getParamOptions($param = null) {

        if(null === $param) {
            return $this->paramOptions;
        }

        if(!isset($this->paramOptions[$param])) {
            throw new RouteParamException("Route param '{$param}' not found!", RouteParamException::PARAM_NAME_NOT_FOUND);
        }
        return $this->paramOptions[$param];
    }

    /**
     * @param null|string $param
     * @return bool
     */
    public function hasParamOptions($param = null) {

        if(null === $param) {
            return !empty($this->paramOptions);
        }

        return isset($this->paramOptions[$param]);

    }

    /**
     * @param array $bindings
     */
    public function setParamOptions(array $bindings) {
        $this->paramOptions = $bindings;
    }

    /**
     * @param RoutingResult $result
     * @return RouteInterface
     */
    public function replaceHandlerBindings(RoutingResult $result) {

        $translate = array();
        foreach($result->getVars() as $key => $binding) {
            $translate['<' . $key . '>'] = $binding;
        }

        $this->setHandler(\strtr($this->getHandler(), $translate));
        return $this;
    }

    public function getSchemeExpression() {

        $expression = '#' . \str_replace('.', '\.', $this->getScheme()) . '#';
        $paramNames = $this->getParamNames();
        $translate = array();

        foreach($paramNames as $paramName) {

            $paramKey = '@' . $paramName;
            if($this->hasParamOptions($paramKey)) {
                $opts = $this->getParamOptions($paramKey);

                if(isset($opts['match'])) {
                    \preg_match_all('#' . self::COUNT_BRACES_EXPRESSION . '#', $opts['match'], $matches);
                    $closingBracesCount = \count($matches[0]);
                    if($closingBracesCount !== 1) {
                        throw new RouteException("Param '{$paramName}' has an own match expression but has {$closingBracesCount} instead of a single (one) grouping!", RouteException::PARAM_MATCH_EXPRESSION_INALID);
                    }
                    $matchExpr = $opts['match'];
                } else {
                    $matchExpr = self::DEFAULT_PARAM_EXPRESSION;
                }

                if(isset($opts['default']) && \strpos($matchExpr, '?') === false) {
                    $matchExpr .= '?';
                }
            } else {
                $matchExpr = self::DEFAULT_PARAM_EXPRESSION;
            }

            $translate['<' . $paramName . '>'] = $matchExpr;
        }

        $finalExpression  = \strtr($expression, $translate);
        return $finalExpression;

    }

    /**
     * @return array
     */
    public function getDefaults() {
        $bindings = $this->getParamOptions();
        $defaults = array();
        foreach($bindings as $paramKey => $opts) {
            if(!empty($opts['default'])) {
                $paramKey = \substr($paramKey, 1);
                $defaults[$paramKey] = $opts['default'];
            }
        }
        return $defaults;
    }

    public function getArrayConfig() {
        return array(
            $this->getName() => array(
                'request' => $this->getScheme(),
                'handler' => $this->getHandler(),
                'passChecker' => $this->getPassCheckerLocator(),
                'bindings' => $this->getParamOptions(),
            )
        );
    }

    /**
     * @static
     * @param  string   $routeName
     * @param  array    $routeData
     * @return Route
     * @throws RouteException
     */
    public static function fromArrayConfig($routeName, array $routeData) {

        if(!isset($routeData['handler'])) {
            throw new RouteException("No route handler defined for '{$routeName}'!", RouteException::ROUTE_CONFIG_INVALID);
        }

        $route = self::create($routeName, $routeData['request']);
        $route->setHandler($routeData['handler']);
        $route->setParamOptions(        isset($routeData['bindings'])    ? $routeData['bindings']       : array()   );
        $route->setPassCheckerLocator(  isset($routeData['passChecker']) ? $routeData['passChecker']    : null      );
        $route->setPriority(            isset($routeData['priority'])    ? $routeData['priority']       : 50        );

        return $route;
    }

    /**
     * @static
     * @param string $name
     * @param string $scheme
     * @return \Shift1\Core\Routing\Route\Route
     */
    public static function create($name, $scheme) {
        return new static($name, $scheme);
    }

}
