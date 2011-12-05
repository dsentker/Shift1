<?php

namespace Shift1\Core\Config\Manager;
use Shift1\Core\Config\iConfigFile;

abstract class AbstractManager implements iConfigManager {

    protected $configData;

    public function __construct(\ArrayObject $configData) {
        $this->setConfigData($configData);
    }

    public function get($key) {
        return $this->getConfigData()->{$key} ?: null;
    }

    public function __get($key) {
        return $this->get($key);
    }

    public function getFromString($strKey) {
        /*
         * $arrKey = \explode('.', $strKey);
         */
        
    }

    public function extendConfig($rootKey, $opts) {
        $configData = (array) $this->getConfigData();
        $configData[$rootKey] = \array_merge_recursive((array) $configData[$rootKey], $opts);
        $this->setConfigData(new \ArrayObject($configData));
    }


    public function setConfigData(\ArrayObject $configData) {
        $this->configData = $configData;
    }

    public function getConfigData() {
        return $this->configData;
    }


}
?>
