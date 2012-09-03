<?php
namespace Shift1\Core\Routing\Route;

use Shift1\Core\Routing\Result\RoutingResult;

interface RouteInterface {

    const PARAM_MATCH_EXPRESSION    = '#<([^<]*)>#';
    const DEFAULT_PARAM_EXPRESSION  = '([A-z0-9-_]+)';
    const COUNT_BRACES_EXPRESSION   = '([^\\\)]\))';

    /**
     * @return string
     */
    function getName();

    /**
     * @param string $handler
     */
    function setHandler($handler);

    /**
     * @return string
     */
    function getHandler();

    /**
     * @return string
     */
    function getScheme();

    /**
     * @return string
     */
    function getSchemeExpression();

    /**
     * @abstract
     * @param integer $position
     * @return string
     */
    function getParamNameByPosition($position);

    /**
     * @abstract
     * @return array
     */
    function getParamNames();

    /**
     * @param null|string $param
     * @return array
     * @throws \Shift1\Core\Routing\Exceptions\RouteParamException
     */
    function getParamOptions($param = null);

    /**
     * @param null|string $param
     * @return bool
     */
    function hasParamOptions($param = null);

    /**
     * @param array $bindings
     */
    function setParamOptions(array $bindings);

    /**
     * @abstract
     * @param RoutingResult $bindings
     * @return RouteInterface
     */
    function replaceHandlerBindings(RoutingResult $result);

    /**
     * @abstract
     * @return array
     */
    function getDefaults();

    /**
     * @abstract
     * @param int $priority
     * @return void
     */
    function setPriority($priority);

    /**
     * @abstract
     * @return int
     */
    function getPriority();


}
