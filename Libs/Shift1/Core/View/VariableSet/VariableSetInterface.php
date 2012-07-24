<?php
namespace Shift1\Core\View\VariableSet;
 
interface VariableSetInterface {

    function add($key, $var);

    function get($key);

    function __get($key);

    function getAll();

    function getKeys();

    function has($key);

}
