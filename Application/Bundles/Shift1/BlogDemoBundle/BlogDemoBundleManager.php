<?php
namespace Bundles\Shift1\BlogDemoBundle;

use Shift1\Core\Bundle\Manager\BundleManager;
use Shift1\Core\Config\Builder\ConfigBuilder;

class BlogDemoBundleManager extends BundleManager {

    public function getServiceLocators() {
        return array(

        );
    }

    public function loadApplicationConfiguration(ConfigBuilder $config) {

        $config
            ->add()


        return $config;
    }

}


?>
(hierarchy)
key
value
needsAdjustment
adjustmentvalidator
