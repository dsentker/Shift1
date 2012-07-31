<?php
namespace Shift1\Core\VariableSet;
 
interface VariableSetInterface {

    function add($key, $var);

    function remove($key);

    function get($key);

    function __get($key);

    function getAll();

    function getKeys();

    function has($key);

    function merge(VariableSetInterface $variableSet);

}
