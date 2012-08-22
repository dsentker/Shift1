<?php
namespace Shift1\Core\Bundle\Converger;

use Shift1\Core\Config\Builder\ConfigBuilder;

class ConfigConverger extends BundleConverger {

    /**
     * @param ConfigBuilder $builder
     * @return void
     */
    public function createConfigFile(ConfigBuilder $builder) {

        foreach($this->getBundleManager() as $bundleManager) {
            /** @var $bundleManager \Shift1\Core\Bundle\Manager\BundleManagerInterface */
            $bundleManager->loadApplicationConfiguration($builder);
        }

        // for testing purposes
        die($builder->dump());

    }

}
