<?php
namespace Shift1\Core\Bundle\Converger;

use Shift1\Core\InternalFilePath;
use Shift1\Core\Bundle\Manager\BundleManagerInterface;
use Shift1\Core\Bundle\Exceptions\ConvergerException;
use Shift1\Core\Config\File\IniFile as ConfigFile;

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
     * @return array|bool
     */
    public function getBundleManager($bundle = null) {

        /*
        if(null === $this->bundleManager) {
            $this->scanBundles();
        }
        */

        if(null === $bundle) {
            // Get all bundles
            return $this->bundleManager;
        } elseif(\is_string($bundle)) {
            return (isset($this->bundleManager[$bundle]) && $this->bundleManager[$bundle] instanceof BundleManagerInterface)
                ? $this->bundleManager[$bundle]
                : false;
        }

        return false;

    }

/*
    protected function scanBundles() {
        $globPattern = $this->bundlePath->getAbsolutePath() . \DIRECTORY_SEPARATOR . '*';
        $foundBundleManager = array();
        $bundleRootNamespace = '\\Bundles\\';

        foreach(\glob($globPattern, \GLOB_ONLYDIR) as $developerFolder) {

            $developer = \basename($developerFolder);

            foreach(\glob($this->bundlePath->getAbsolutePath() . \DIRECTORY_SEPARATOR . $developer . \DIRECTORY_SEPARATOR . '*', \GLOB_ONLYDIR) as $bundleFolder) {

                $bundleName = \basename($bundleFolder);
                $bundleNamespace = $developer . '\\' . $bundleName;
                $bundleManagerClassname = $bundleName . 'Manager';
                $bundleManagerNamepsace = $bundleRootNamespace . $bundleNamespace . '\\' . $bundleManagerClassname;
                if(!\class_exists($bundleManagerNamepsace)) {
                    throw new ConvergerException("Bundle Manager for bundle '{$bundleNamespace}' does not exist (tried to load manager via '{$bundleManagerNamepsace}').", ConvergerException::BUNDLE_MANAGER_NOT_FOUND);
                }
                $foundBundleManager[$bundleNamespace] = new $bundleManagerNamepsace;
            }

        }

        $this->bundleManager = $foundBundleManager;

    }
*/

}
