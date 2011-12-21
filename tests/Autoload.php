<?php

function Shift1TestAutoloader($class) {

    $class = ltrim($class, '\\');

    $segments = preg_split('#[\\\\_]#', $class);
    $rootNs = array_shift($segments);

    switch ($rootNs) {
        case 'Shift1Test':
            $path = dirname(__DIR__) . '/Tests/Shift1/';
            break;
        case 'Shift1':
            $path = dirname(__DIR__) . '/Libs/Shift1/';
            break;
        default:
            $path = false;
            break;
    }

    if ($path) {
        $path .= implode('/', $segments) . '.php';
        if (file_exists($path)) {
            return include_once $path;
        }
    }


    return false;
}

spl_autoload_register('Shift1TestAutoloader');