<?php
namespace Shift1\Core\Bundle\Converger;

use Shift1\Core\InternalFilePath;
use Shift1\Core\Bundle\Manager\BundleManagerInterface;
use Shift1\Core\Bundle\Exceptions\ConvergerException;

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
     * @param string $projectBundlePath
     */
    public function __construct($projectBundlePath = '/') {
        $this->bundlePath = new InternalFilePath($projectBundlePath);
    }

    /**
     * @param null|string $bundle
     * @return array|bool
     */
    public function getBundleManager($bundle = null) {

        if(null === $this->bundleManager) {
            $this->scanBundles();
        }

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

}
