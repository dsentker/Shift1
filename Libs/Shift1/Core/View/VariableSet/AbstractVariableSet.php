<?php
namespace Shift1\Core\View\VariableSet;

class AbstractVariableSet implements VariableSetInterface {

    protected $vars;

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

    public function __iset($key) {
        return $this->has($key);
    }

    public function getKeys() {
        return \array_keys($this->vars);
    }

    public function getAll() {
        return $this->vars;
    }



}
