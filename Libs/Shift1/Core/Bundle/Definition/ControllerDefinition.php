<?php
namespace Shift1\Core\Bundle\Definition;

use Shift1\Core\Bundle\Exceptions\DefinitionException;

class ControllerDefinition extends BundleDefinition {

    const CONTROLLER_SUFFIX = 'Controller';

    protected $controllerDefinition;
    protected $controllerName;

    public function __construct($definition) {

        // Something like vendor:bundleName:controllerName
        $parts = \explode(':', $definition);
        if(!isset($parts[2]) || isset($parts[3])) {
            throw new DefinitionException("A controller definition must have a scheme like 'vendor:bundle:controller', '{$definition}' given!", DefinitionException::BUNDLE_DEFINITION_INVALID);
        }

        $this->controllerDefinition = $definition;
        $this->controllerName = \ucfirst(\array_pop($parts));
        $bundleDefinition = \implode(':', $parts);

        parent::__construct($bundleDefinition);
    }

    public static function fromNamespace($namespace) {

        // Something like \Vendor\BundleName\Controller\FooController
        $namespace = \trim($namespace, '\\');
        $namespaceParts = \explode('\\', $namespace);
        $controllerNameSuffixed = \array_pop($namespaceParts);
        $controllerName = self::removeSuffix($controllerNameSuffixed, self::CONTROLLER_SUFFIX);

        $parentNamespace = \array_pop($namespaceParts);
        if($parentNamespace !== 'Controller') {
            throw new DefinitionException("A controller's parent namespace must be 'Controller', '{$parentNamespace}' given!'", DefinitionException::CONTROLLER_NAMESPACE_INVALID);
        }

        $bundleNamespace = \implode('\\', $namespaceParts);
        $bundleDefinition = parent::fromNamespace($bundleNamespace)->getBundleDefinition();
        $controllerDefinition = $bundleDefinition . ':' . $controllerName;
        return new self($controllerDefinition);

    }

    public function getNamespace() {
        return parent::getNamespace() . '\\' . 'Controller' . '\\' . $this->getControllerName();
    }

    public function getControllerDefinition() {
        return $this->controllerDefinition;
    }

    public function getControllerName($suffixed = true) {
        return $suffixed ? $this->controllerName . self::CONTROLLER_SUFFIX : $this->controllerName;
    }



}
