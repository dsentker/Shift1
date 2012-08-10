<?php
namespace Shift1\Core\Bundle\Definition;

use Shift1\Core\Bundle\Exceptions\DefinitionException;

class ActionDefinition extends ControllerDefinition {

    const ACTION_SUFFIX = 'Action';

    protected $actionDefinition;
    protected $actionName;

    public function __construct($definition) {

        // Something like vendor:bundleName:controllerName::fooAction
        $parts = \explode('::', $definition);
        if(!isset($parts[1]) || isset($parts[2])) {
            throw new DefinitionException("A action definition must have a scheme like 'vendor:bundle:controller:action', '{$definition}' given!", DefinitionException::ACTION_DEFINITION_INVALID);
        }

        $this->actionDefinition = $definition;
        $this->actionName = \array_pop($parts);
        $controllerDefinition = $parts[0];

        parent::__construct($controllerDefinition);
    }



    public function getActionDefinition() {
        return $this->actionDefinition;
    }

    public function getActionName($suffixed = true) {
        return $suffixed? $this->actionName . self::ACTION_SUFFIX : $this->actionName;
    }

    public function getTemplateDefinition() {
        $templateDefinition = $this->getBundleDefinition() . ':' . $this->getActionName(false);
        return new TemplateDefinition($templateDefinition);
    }

}
