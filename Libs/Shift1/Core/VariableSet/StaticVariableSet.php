<?php
namespace Shift1\Core\VariableSet;

use Shift1\Core\VariableSet\Exceptions\VariableSetException;

class StaticVariableSet extends VariableSet {

    public function add($key, $var) {

        if($this->has($key)) {
            throw new VariableSetException('No modification allowed.');
        } else  {
            parent::add($key, $var);
        }
    }

    public function remove($key) {
        throw new VariableSetException('Removing is not allowed.');
    }

    public function merge(VariableSetInterface $variableSet) {
        throw new VariableSetException('Merging is not allowed.');
    }



}
