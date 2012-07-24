<?php
namespace Shift1\Core\View\VariableSet;

use Shift1\Core\Service\ContainerAccess;
use Shift1\Core\Service\Container\ServiceContainerInterface;
 
class AbstractVariableSet implements VariableSetInterface, ContainerAccess {

    protected $vars;

    protected $container;

    public function add($key, $var) {
        $this->vars[$key] = $var;
    }

    public function get($key) {
        return (isset($this->vars[$key])) ? $this->vars[$key] : null;
    }

    public function __get($key) {
        return $this->get($key);
    }

    public function has($key) {
        return (!empty($this->vars[$key]));
    }

    public function getKeys() {
        return \array_keys($this->vars);
    }

    public function getAll() {
        return $this->vars;
    }

    public function setContainer(ServiceContainerInterface $container) {
        $this->container = $container;
    }

    function getContainer() {
        return $this->container;
    }

}
