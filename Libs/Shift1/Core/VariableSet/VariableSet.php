<?php
namespace Shift1\Core\VariableSet;

use Shift1\Core\VariableSet\Exceptions\VariableSetException;

class VariableSet implements VariableSetInterface {

    /**
     * @var array
     */
    protected $vars;

    public function add($key, $var) {
        $this->vars[$key] = $var;
    }

    public function remove($key) {
        if($this->has($key)) {
            unset($this->vars[$key]);
            return true;
        }
        return false;
    }

    public function get($key) {
        return (isset($this->vars[$key])) ? $this->vars[$key] : null;
    }

    public function __get($key) {
        return $this->get($key);
    }

    public function __set($key, $var) {
        $this->add($key, $var);
    }

    public function has($key) {
        return (!empty($this->vars[$key]));
    }

    public function __isset($key) {
        return $this->has($key);
    }

    public function getKeys() {
        return \array_keys($this->vars);
    }

    /**
     * @return array
     */
    public function getVars() {
        return $this->vars;
    }

    public function merge(VariableSetInterface $variableSet) {
        $this->vars = \array_merge($this->getVars(), $variableSet->getVars());
    }

    /**
     * @param string $key
     * @param mixed $var
     * @throws Exceptions\VariableSetException
     * @return void
     */
    public function modify($key, $var) {
        if(!$this->has($key)) {
            throw new VariableSetException("Could not modify key '{$key}': Key does not exist.", VariableSetException::MODIFYING_FAILED);
        }
        $this->vars[$key] = $var;
    }

    public function mergeArray(array $vars) {
        foreach($vars as $key => $var) {
            $this->add($key, $var);
        }
    }

    /**
     * @static
     * @param array $vars
     * @return VariableSet
     */
    public static function fromArray(array $vars) {
        $set = new static;
        $set->mergeArray($vars);
        return $set;
    }



}
