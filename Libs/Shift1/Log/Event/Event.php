<?php
namespace Shift1\Log\Event;
 
class Event {

    /**
     * @var string
     */
    protected $timestamp;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var int
     */
    protected $errorLevel;

    /**
     * @var string
     */
    protected $errorLevelName;


    /**
     * @param int $errorLevel
     */
    public function setErrorLevel($errorLevel) {
        $this->errorLevel = $errorLevel;
    }

    /**
     * @return int
     */
    public function getErrorLevel() {
        return $this->errorLevel;
    }

    /**
     * @param string $errorLevelName
     */
    public function setErrorLevelName($errorLevelName) {
        $this->errorLevelName = $errorLevelName;
    }

    /**
     * @return string
     */
    public function getErrorLevelName() {
        return $this->errorLevelName;
    }

    /**
     * @param string $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @param string $timestamp
     */
    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }

    /**
     * @return string
     */
    public function getTimestamp() {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->timestamp . ': (' . $this->getErrorLevelName() . ') ' . $this->getMessage();
    }
}