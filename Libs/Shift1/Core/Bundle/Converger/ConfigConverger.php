<?php
namespace Shift1\Core\Bundle\Converger;

use Shift1\Core\Config\Builder\ConfigTreeBuilder;

class ConfigConverger extends BundleConverger {

    /**
     * @var null|\Closure|\Callable
     */
    protected $adjustmentRequestHandler = null;

    /**
     * @param ConfigTreeBuilder $builder
     * @return ConfigTreeBuilder
     */
    public function getBundleConfiguration(ConfigTreeBuilder $builder) {

        $adjustmentRequestHandler = $this->getAdjustmentRequestHandler();

        foreach($this->getBundleManager() as $bundleManager) {

            /**
             * @var $bundleManager \Shift1\Core\Bundle\Manager\BundleManagerInterface
             * @var $bundleConfig ConfigTreeBuilder
             */
            $bundleConfig = $bundleManager->loadApplicationConfiguration();

            if(!$bundleConfig->isEmpty()) {

                foreach($bundleConfig->getAdjustmentRequests() as $req) {

                    $bundleConfig->node('.'); // set to root
                    $iterationCount = 1;
                    while( false === $adjustmentRequestHandler($req, $bundleConfig, $iterationCount)) {
                        $bundleConfig->node('.'); // set to root
                        $iterationCount++;
                    }


                }

                $bundleRoot = \strtolower($bundleManager->getVendor() . '.' . $bundleManager->getBundleName());
                $builder->addItem($bundleRoot, $bundleConfig->getConfig());

            }

        }

        return $builder;
    }

    /**
     * @param \Callable|\Closure $handler
     */
    public function setRequestAdjustmentHandler(\Closure $handler) {
        $this->adjustmentRequestHandler = $handler;
    }

    /**
     * @return \Callable|\Closure
     */
    public function getAdjustmentRequestHandler() {

        if(null === $this->adjustmentRequestHandler) {
            die('No handler set.'); /** @todo throw exception here */
        }

        return $this->adjustmentRequestHandler;
    }

}