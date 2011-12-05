<?php
namespace Shift1\Debug;

use Shift1\Debug\Logger\iLogger;

class Debug implements iDebug {

    /**
     * @var array
     */
    protected $logger = array();

    /**
     * @param Logger\iLogger $logger
     * @param $id
     * @return Debug
     */
    public function addLogger(iLogger $logger, $id) {
        $this->logger[$id] = $logger;
        return $this;
    }

    /**
     * @param $id
     * @return iLogger
     */
    public function getLoggerById($id) {
        return $this->logger[$id];
    }

    /**
     * @return array
     */
    public function getLogger() {
        return $this->logger;
    }

    /**
     * @param $id
     * @return Debug
     */
    public function removeLogger($id) {
        if($this->loggerExists($id)) {
            unset($this->logger[$id]);
        }
        return $this;
    }

    /**
     * @param $id
     * @return bool
     */
    public function loggerExists($id) {
        return isset($this->logger[$id]);
    }

    public function log($text, $level) {
        foreach($this->getLogger() as $logger) {
            $logger->write($text);
        }
    }

}
