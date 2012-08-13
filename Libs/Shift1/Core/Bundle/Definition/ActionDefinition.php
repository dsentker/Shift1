<?php
namespace Shift1\Core\Bundle\Definition;

use Shift1\Core\Bundle\Exceptions\DefinitionException;
use Shift1\Core\InternalFilePath;

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

    public static function fromTemplateFile(InternalFilePath $filepath) {
        /*
         * Something like
         * /www/shift1/application/bundles/vendorA/demoXBundle/views/index.tpl.php
         * or
         * /www/shift1/application/bundles/vendorB/demoYBundle/views/subfolder/index.tpl.php
         *
         * @TODO
         */
        $path = $filepath->getAbsolutePath();
        $identificator = 'Application' . \DIRECTORY_SEPARATOR . 'Bundles' . \DIRECTORY_SEPARATOR;
        $pos = strpos($path, $identificator) + strlen($identificator);
        $root = substr($path, $pos);
        if(false === \strpos($root, \DIRECTORY_SEPARATOR)) {
            throw new DefinitionException("Template path not valid: '{$root}', extracted from {$path}.", DefinitionException::TEMPLATE_PATH_INVALID);
        }
        $pathParts = \explode(\DIRECTORY_SEPARATOR, $root);
        /*
         * Something like vendorA\demoYBundle\Views\subfolder\index.tpl.php
         */
        if(!isset($pathParts[2]) || $pathParts[2] != 'Views') {
            throw new DefinitionException("Template path not valid: Expected '{$pathParts[1]}/Views', got '{$pathParts[1]}/{$pathParts[2]}'!", DefinitionException::TEMPLATE_PATH_INVALID);
        } elseif(!isset($pathParts[4])) {
            throw new DefinitionException("Template path not valid: This template must be in a subfolder to detect an controller name! (got '{$root}'", DefinitionException::TEMPLATE_PATH_INVALID);
        }

        $vendorName =       \strtolower($pathParts[0]);
        $bundleName =       \strtolower(self::removeSuffix($pathParts[1], self::BUNDLE_SUFFIX));
        $controllerName =   \strtolower($pathParts[3]);
        $actionName =       \strtolower(self::removeSuffix($pathParts[4], TemplateDefinition::TEMPLATE_EXT));

        $definition = "{$vendorName}:{$bundleName}:{$controllerName}::{$actionName}";
        return new static($definition);

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
