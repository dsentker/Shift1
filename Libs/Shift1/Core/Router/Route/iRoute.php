<?php
namespace Shift1\Core\Router\Route;

interface iRoute {

    const KEYBINDING_CHAR = '@';
    const URI_SEGMENT_SEPARATOR = '/';
    const URI_PARAM_KEY_SEPARATOR = ':';
    const DEFAULT_SEGMENT_EXPRESSION = '.*';


    public function getBindings();

    public function getBinding($bind);

    public function getScheme();

    public function getSchemeAsPattern();

    public function isBindedSegment($routeSegment);


}
