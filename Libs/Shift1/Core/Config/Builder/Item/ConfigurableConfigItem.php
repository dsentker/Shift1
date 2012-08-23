<?php
namespace Shift1\Core\Config\Builder\Item;

use Shift1\Core\Config\Exceptions\BuilderException;
 
class ConfigurableConfigItem extends ConfigItem {

    /**
     * @var string
     */
    protected $prompt;

    /**
     * @var bool
     */
    protected $needsInput = false;

    /**
     * @var null|\Closure
     */
    protected $validatorCallback = null;

    /**
     * @var string
     */
    protected $errorMessage = '';

    /**
     * @param string $prompt
     */
    public function setPrompt($prompt) {
        $this->prompt = (string) $prompt;
    }


    /**
     * @return string
     */
    public function getPrompt() {
        return $this->prompt;
    }

    /**
     * @return bool
     */
    public function getNeedValueInput() {
        return $this->needsInput;
    }

    /**
     * @param string $prompt                    The prompt to ask for the specific value
     * @param null|string|\Closure $validator   If a string is given, it will be used as a regular
     *                                          expression
     * @param string $errorMessage              The error message which will be shown if the validation fails
     * @return ConfigurableConfigItem
     * @throws \Shift1\Core\Config\Exceptions\BuilderException
     */
    public function needValueInput($prompt, $validator = null, $errorMessage = 'Please check your input') {

        if($validator instanceof \Closure) {
            $this->validatorCallback = $validator;
        } elseif(\is_string($validator)) {
            $this->validatorCallback = function($input) use ($validator) {
                return (\preg_match($validator, $input) === 1);
            };
        } elseif(null !== $validator) {
            throw new BuilderException("Validator for config key '{$this->getKey()}' is invalid. Given " . gettype($validator) . ", expected a string or closure!");
        }

        $this->prompt = $prompt;
        $this->errorMessage = $errorMessage;
        $this->needsInput = true;
        return $this;
    }

    public function getErrorMessage() {
        return $this->errorMessage;
    }

    /**
     * @return null|\Closure
     */
    public function getValidatorCallback() {
        return $this->validatorCallback;
    }

    /**
     * @return bool
     */
    public function hasValidatorCallback() {
        return ($this->validatorCallback instanceof \Closure);
    }

}
