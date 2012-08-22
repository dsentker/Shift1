<?php
namespace Shift1\Core\Config\Builder\Item;
 
class ConfigItem {

    /**
     * @var string
     */
    protected $key;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param string $key
     */
    public function __construct($key) {
        $this->key = $key;
    }

    /**
     * @param mixed $value
     * @return ConfigItem
     */
    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Acts as a factory
     * @static
     * @param string $key
     * @return ConfigItem|ConfigurableConfigItem
     */
    public static function create($key) {
        return new static($key);
    }

}
