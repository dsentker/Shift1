<?php
namespace Shift1\Core\Router\Route;

interface RouteInterface {

    const KEYBINDING_CHAR = '@';
    const URI_SEGMENT_SEPARATOR = '/';
    const URI_PARAM_KEY_SEPARATOR = ':';
    const DEFAULT_SEGMENT_EXPRESSION = '.*';

    /**
     * @abstract
     * @return array
     */
    public function getBindings();

    /**
     * @abstract
     * @param $bind
     * @return array
     */
    public function getBinding($bind);

    /**
     * @abstract
     * @param string $bind
     * @return bool
     */
    public function hasBinding($bind);

    /**
     * @abstract
     * @return string
     */
    public function getScheme();

    /**
     * @abstract
     * @return string
     */
    public function getSchemeAsPattern();

    /**
     * @abstract
     * @param $routeSegment
     * @return bool
     */
    public function isBindedSegment($routeSegment);

    /**
     * @abstract
     * @return array
     */
    public function getSchemeSegments();

}