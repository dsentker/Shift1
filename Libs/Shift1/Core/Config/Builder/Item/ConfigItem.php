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

    protected $prompt;

    protected $needsInput = false;

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

    public function getPrompt() {
        return $this->prompt;
    }

    public function getNeedValueInput() {
        return $this->needsInput;
    }

    public function needValueInput($prompt, $validator) {
        $this->prompt = $prompt;
        $this->needsInput = true;
        return $this;
    }

    /**
     * Acts as a factory
     * @static
     * @param string $key
     * @return ConfigItem
     */
    public static function create($key) {
        return new static($key);
    }

}
