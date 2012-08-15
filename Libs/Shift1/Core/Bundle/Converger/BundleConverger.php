<?php
namespace Shift1\Core\Bundle\Converger;

use Shift1\Core\InternalFilePath;
use Shift1\Core\Bundle\Manager\BundleManagerInterface;
use Shift1\Core\Bundle\Exceptions\ConvergerException;
use Shift1\Core\Config\File\IniFile as ConfigFile;
use Shift1\Core\Service\Container\ServiceContainer;

class BundleConverger {

    /**
     * @var \Shift1\Core\InternalFilePath
     */
    protected $bundlePath;

    /**
     * @var null|array
     */
    protected $bundleManager = null;

    /**
     * @param array  $bundleSetDefinition
     * @param string $projectBundlePath
     * @throws \Shift1\Core\Bundle\Exceptions\ConvergerException
     */
    public function __construct(array $bundleSetDefinition, $projectBundlePath) {

        $this->bundlePath = new InternalFilePath($projectBundlePath);
        $foundBundleManager = array();

        foreach($bundleSetDefinition as $bundlePath) {
            $bundlePathParts = \explode('\\', $bundlePath);

            if(!isset($bundlePathParts[1])) {
                throw new ConvergerException("Bundle definition incomplete: '{$bundlePath}' - Part 2 is missing", ConvergerException::BUNDLE_DEFINITION_ERROR);
            }

            $bundleVendor = $bundlePathParts[0];
            $bundleName =   $bundlePathParts[1];

            $bundleRelativeNamespace = '\\Bundles\\' . $bundlePath;
            $bundleManagerNamespace = $bundleRelativeNamespace . '\\' . $bundleName . 'Manager';

            if(!\class_exists($bundleManagerNamespace)) {
                throw new ConvergerException("Bundle Manager for bundle '{$bundlePath}' does not exist (tried to load manager via '{$bundleManagerNamespace}').", ConvergerException::BUNDLE_MANAGER_NOT_FOUND);
            }

            $foundBundleManager[$bundlePath] = new $bundleManagerNamespace;

        }

        $this->bundleManager = $foundBundleManager;

    }

    public static function factory($environment) {
        $projectBundlePath = 'Application\Bundles';
        $bundleConfigurationPath = new InternalFilePath('Application/Config/bundles.ini');
        $bundleConfiguration = new ConfigFile($bundleConfigurationPath->getAbsolutePath());
        $bundleSetDefinitions = $bundleConfiguration->toArray();
        $bundleSetDefinition = isset($bundleSetDefinitions[$environment]['bundles']) ? $bundleSetDefinitions[$environment]['bundles'] : array();
        return new static($bundleSetDefinition, $projectBundlePath);
    }

    /**
     * @param null|string $bundle
     * @return array|bool|BundleManagerInterface
     */
    public function getBundleManager($bundle = null) {

        if(null === $bundle) {
            // Get all bundle managers in an array
            return $this->bundleManager;
        } elseif(\is_string($bundle)) {
            return (isset($this->bundleManager[$bundle]) && $this->bundleManager[$bundle] instanceof BundleManagerInterface)
                ? $this->bundleManager[$bundle]
                : false;
        }

        return false;

    }

    /**
     * @param \Shift1\Core\Service\Container\ServiceContainer $container
     * @return \Shift1\Core\Service\Container\ServiceContainer
     */
    public function convergeServiceLocators(ServiceContainer $container) {

        foreach($this->getBundleManager() as $bundleManager) {
            /** @var $bundleManager \Shift1\Core\Bundle\Manager\BundleManagerInterface */
            $bundleManager->loadServiceLocators($container);
        }

        return $container;

    }


}
