<?php
namespace Shift1\Core\VariableSet;

use Shift1\Core\VariableSet\Exceptions\VariableSetException;

class StaticVariableSet extends VariableSet {

    public function add($key, $var) {

        if($this->has($key)) {
            throw new VariableSetException('No modification allowed.', VariableSetException::READ_ONLY);
        } else  {
            parent::add($key, $var);
        }
    }

    public function remove($key) {
        throw new VariableSetException('Removing is not allowed.', VariableSetException::READ_ONLY);
    }

    public function merge(VariableSetInterface $variableSet) {
        throw new VariableSetException('Merging is not allowed.', VariableSetException::READ_ONLY);
    }

    public function modify($key, $val) {
        throw new VariableSetException('Modifying is not allowed.', VariableSetException::READ_ONLY);
    }



}
