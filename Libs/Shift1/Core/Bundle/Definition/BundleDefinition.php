<?php
namespace Shift1\Core\Bundle\Definition;

use Shift1\Core\Bundle\Exceptions\DefinitionException;

class BundleDefinition {

    const BUNDLE_SUFFIX = 'Bundle';

    protected $bundleDefinition;
    protected $bundleName;
    protected $bundleVendor;

    public function __construct($definition) {

        $this->bundleDefinition = $definition;
        $parts = \explode(':', $definition);

        if(!isset($parts[1]) || isset($parts[2])) {
            throw new DefinitionException("A bundle definition must have a scheme like 'vendor:bundle', '{$definition}' given!", DefinitionException::BUNDLE_DEFINITION_INVALID);
        }

        $this->bundleVendor = \ucfirst($parts[0]);
        $this->bundleName = \ucfirst($parts[1]);

    }

    public static function fromNamespace($namespace) {

        $bundleNamespace = \trim($namespace, '\\');
        $bundleNamespaceParts = \explode('\\', $bundleNamespace);

        if(!isset($bundleNamespaceParts[1])) {
            throw new DefinitionException("A bundle namespace must have a vendor namespace and a sub-namespace with the bundle name, e.g. 'Vendor\\BundleName\\'!", DefinitionException::BUNDLE_NAMESPACE_INVALID);
        }

        $bundleName = \array_pop($bundleNamespaceParts);
        if(false === \strpos($bundleName, self::BUNDLE_SUFFIX)) {
            $suffix = self::BUNDLE_SUFFIX;
            throw new DefinitionException("A bundle name must have the '{$suffix}'-Suffix  , e.g. 'ExampleBundle'!", DefinitionException::BUNDLE_SUFFIX_INVALID);
        }
        $bundleName = static::removeSuffix($bundleName, self::BUNDLE_SUFFIX);

        $bundleVendor = \implode('\\', $bundleNamespaceParts);

        if(false !== \strpos($bundleVendor, '\\')) {
            throw new DefinitionException("A bundle vendor must not use a sub-namespace!", DefinitionException::BUNDLE_VENDOR_INVALID);
        }

        $definition = $bundleName . ':' . $bundleVendor;

        return new static($definition);

    }

    public function getBundleDefinition() {
        return $this->bundleDefinition;
    }

    public function getBundleName($suffixed = true) {
        return $suffixed ? $this->bundleName . self::BUNDLE_SUFFIX : $this->bundleName;
    }

    public function getVendorName() {
        return $this->bundleVendor;
    }

    public function getNamespace() {
        return $this->getBundleNamespace();
    }

    public function getBundleNamespace() {
        return 'Bundles\\' . $this->getVendorName() . '\\' . $this->getBundleName();
    }

    protected static function removeSuffix($subject, $suffix) {
        $suffixPos = \strrpos($subject, $suffix);
        return \substr($subject, 0, $suffixPos);
    }

}
