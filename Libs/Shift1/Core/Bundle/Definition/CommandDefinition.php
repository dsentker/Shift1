<?php
namespace Shift1\Core\Bundle\Definition;

use Shift1\Core\Bundle\Exceptions\DefinitionException;
use Shift1\Core\InternalFilePath;

class CommandDefinition extends ActionDefinition {

    public function getNamespace() {
        return $this->getBundleNamespace() . '\\' . 'Console' . '\\' . $this->getControllerName();
    }

}
