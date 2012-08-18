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
    protected $passCheckerNamespace;

    /**
     * @var array
     */
    protected $paramOptions = array();

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
     * @return string
     */
    public function getScheme() {
        return $this->scheme;
    }

    /**
     * @param string $passCheckerNamespace
     */
    public function setPassCheckerNamespace($passCheckerNamespace) {
        $this->passCheckerNamespace = $passCheckerNamespace;
    }

    /**
     * @return string
     */
    public function getPassCheckerNamespace() {
        return $this->passCheckerNamespace;
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


}
