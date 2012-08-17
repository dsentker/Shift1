<?php
namespace Shift1\Core\Config\Manager;

abstract class AbstractManager implements ConfigManagerInterface {

    /**
     * @var \ArrayObject
     */
    protected $configData;

    public function __construct(\ArrayObject $configData) {
        $this->setConfigData($configData);
    }

    /**
     * @param string $key
     * @return null|string
     */
    public function get($key) {
        return $this->getConfigData()->{$key} ?: null;
    }

    /**
     * @param $key
     * @return null|string
     */
    public function __get($key) {
        return $this->get($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value) {
        $this->configData[$key] = $value;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value) {
        $this->set($key, $value);
    }

    /**
     * @param string $rootKey
     * @param array $opts
     * @return void
     */
    public function extendConfig($rootKey, array $opts) {
        $configData = (array) $this->getConfigData();
        $configData[$rootKey] = \array_merge_recursive((array) $configData[$rootKey], $opts);
        $this->setConfigData(new \ArrayObject($configData));
    }

    /**
     * @param \ArrayObject $configData
     * @return void
     */
    public function setConfigData(\ArrayObject $configData) {
        $this->configData = $configData;
    }

    /**
     * @return \ArrayObject
     */
    public function getConfigData() {
        return $this->configData;
    }

    /**
     * @return mixed
     */
    public function dump() {
        return \var_export($this->configData);
    }

}