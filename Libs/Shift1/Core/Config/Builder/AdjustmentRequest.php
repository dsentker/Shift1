<?php
namespace Shift1\Core\Config\Builder;

use Shift1\Core\Config\Exceptions\AdjustmentRequestException;

class AdjustmentRequest {

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $prompt;

    /**
     * @var \Closure|\Callable
     */
    protected $validatorCallback = null;

    /**
     * @var string
     */
    protected $validationFailedMessage;

    /**
     * @param $subject
     * @throws Shift1\Core\Config\Exceptions\AdjustmentRequestException if the subject is not valid
     * @return AdjustmentRequest
     */
    public function setSubject($subject) {
        if(!\is_string($subject)) {
            throw new AdjustmentRequestException(\sprintf("The subject must be a string, %s given!", \gettype($subject)),
                \Shift1\Core\Config\Exceptions\AdjustmentRequestException::SUBJECT_INVALID);
        } elseif(empty($subject)) {
            throw new AdjustmentRequestException("The subject must not be empty!", \Shift1\Core\Config\Exceptions\AdjustmentRequestException::SUBJECT_EMPTY);
        }
        $this->subject = $subject;
        return $this;
    }

        /**
     * @return string
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * @param callable|string   $callback
     * @param string            $message
     * @return AdjustmentRequest
     * @throws Shift1\Core\Config\Exceptions\AdjustmentRequestException if the first parameter $callback is not valid
     */
    public function setValidation($callback, $message = 'No valid input given.') {
        if(\is_string($callback)) {
            $pattern = $callback;
            $callback = function($input) use ($pattern) {
                return \preg_match($pattern, $input) === 1;
            };
        } elseif($callback instanceof \Closure) {
            throw new AdjustmentRequestException(\sprintf(
                    "The validator for %s must be a string pattern or a callable closure, %s given!",
                    $this->getSubject(),
                    \gettype($callback)
                ), \Shift1\Core\Config\Exceptions\AdjustmentRequestException::VALIDATOR_CALLBACK_INVALID);
        }

        $this->validatorCallback = $callback;
        $this->validationFailedMessage = $message;
        return $this;
    }

    /**
     * @param string $prompt
     * @return AdjustmentRequest
     */
    public function setPrompt($prompt) {
        $this->prompt = $prompt;
        return $this;
    }


    /**
     * @return string
     */
    public function getPrompt() {
        return empty($this->prompt) ? \sprintf('Please enter a value for key %s', $this->getSubject()) : $this->prompt;
    }

    /**
     * @return string
     */
    public function getValidationFailedMessage() {
        return $this->validationFailedMessage;
    }

    /**
     * @return callable|\Closure
     */
    public function getValidatorCallback() {
        return $this->validatorCallback;
    }

    /**
     * @static
     * @param string $subject
     * @param string $prompt
     * @return AdjustmentRequest
     */
    public static function factory($subject, $prompt = null) {
        $adjustmentReq = self::create();
        $adjustmentReq->setSubject($subject);
        $adjustmentReq->setPrompt($prompt);
        return $adjustmentReq;
    }

    /**
     * @static
     * @return AdjustmentRequest
     */
    public static function create() {
        return new static;
    }




}
