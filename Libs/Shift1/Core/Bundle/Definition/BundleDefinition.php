<?php
namespace Shift1\Core\Bundle\Definition;

use Shift1\Core\Bundle\Exceptions\DefinitionException;

class BundleDefinition {

    const BUNDLE_SUFFIX = 'Bundle';

    /**
     * @var string
     */
    protected $bundleDefinition;

    /**
     * @var string
     */
    protected $bundleName;

    /**
     * @var string
     */
    protected $bundleVendor;

    /**
     * @param string $definition A definition like bundleVendor:bundleName
     * @throws DefinitionException if the given $definition is not valid
     */
    public function __construct($definition) {

        $this->bundleDefinition = $definition;
        $parts = \explode(':', $definition);

        if(!isset($parts[1]) || isset($parts[2])) {
            throw new DefinitionException("A bundle definition must have a scheme like 'vendor:bundle', '{$definition}' given!", DefinitionException::BUNDLE_DEFINITION_INVALID);
        }

        $this->bundleVendor = \ucfirst($parts[0]);
        $this->bundleName = \ucfirst($parts[1]);

    }

    /**
     * @static
     * @param string $namespace
     * @return BundleDefinition
     * @throws DefinitionException if the given Namespace was not usable
     */
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

    /**
     * @return string
     */
    public function getBundleDefinition() {
        return $this->bundleDefinition;
    }

    /**
     * @param bool $suffixed
     * @return string
     */
    public function getBundleName($suffixed = true) {
        return $suffixed ? $this->bundleName . self::BUNDLE_SUFFIX : $this->bundleName;
    }

    /**
     * @return string
     */
    public function getVendorName() {
        return $this->bundleVendor;
    }

    /**
     * @return string
     */
    public function getNamespace() {
        return $this->getBundleNamespace();
    }

    /**
     * @return string
     */
    public function getBundleNamespace() {
        return 'Bundles\\' . $this->getVendorName() . '\\' . $this->getBundleName();
    }

    /**
     * Removes the string $suffix from $subject
     * @static
     * @param $subject
     * @param $suffix
     * @return string
     */
    protected static function removeSuffix($subject, $suffix) {
        $suffixPos = \strrpos($subject, $suffix);
        return \substr($subject, 0, $suffixPos);
    }

}
