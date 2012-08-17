<?php
namespace Shift1\Log;

use Shift1\Log\Exceptions\LoggerException;

class Logger extends AbstractLogger {

    /**
     * @var array
     */
    protected $writer = array();

    /**
     * @var string
     */
    protected $format = "%timestamp%  %levelname%(%errorlevel%): %message%";

    /**
     * @var string
     */
    protected $timestampFormat = 'Y-m-d H:i:s';

    /**
     * @var array
     */
    protected $errorHandlerMapping = array();

    /**
     * @var bool
     */
    protected $errorHandlerStopChain;

    /**
     * @var int
     */
    protected $logCount = 0;

    /**
     * 
     */
    public function __construct() {
        \register_shutdown_function(array($this, 'executeShutdown'));
    }

    /**
     * @param string $format
     * @return void
     */
    public function setFormat($format) {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     * @return int
     */
    public function getLogCount() {
        return $this->logCount;
    }

    /**
     * @return int
     */
    public function increaseLogCount() {
        return ++$this->logCount;
    }

    /**
     * @param string $format
     * @return void
     */
    public function setTimestampFormat($format) {
        $this->timestampFormat = $format;
    }

    /**
     * @return string
     */
    public function getTimestampFormat() {
        return $this->timestampFormat;
    }

    /**
     * @param Writer\LogWriterInterface $writer
     * @return void
     */
    public function addWriter(Writer\LogWriterInterface $writer) {
        $this->writer[] = $writer;
    }

    /**
     * @return array
     */
    public function getWriter() {
        return $this->writer;
    }

    /**
     * @param string $msg
     * @param int|string $level
     * @return int
     */
    public function log($msg, $level = 'debug') {

        if(\is_int($level)) {
            $errLevel = $level;
            $errLevelName = $this->getLevelName($level);
        } elseif(\is_string($level)) {
            $errLevel = $this->getLevel($level);
            $errLevelName = $level;
        } else {
            throw new LoggerException('Can\'t identify log level "' . $level . '"', LoggerException::ERROR_LEVEL_INVALID);
        }

        $event = $this->createEvent($msg, $errLevel, $errLevelName);

        foreach($this->getWriter() as $writer) {

            /** @var Writer\AbstractWriter $writer */
            if((int) $errLevel <= $this->getLevel($writer->getLevel())) {
                $writer->addEvent($event);
            }

        }

        return $this->increaseLogCount();
    }

    /**
     * @param string $msg
     * @param int $level
     * @param string $levelName
     * @return Event\Event
     */
    protected function createEvent($msg, $level, $levelName) {
        $event = new Event\Event();
        $event->setMessage($msg);
        $event->setErrorLevel($level);
        $event->setErrorLevelName($levelName);
        $event->setTimestamp(\date($this->getTimestampFormat()));
        return $event;
    }

    /**
     * Is called via register_shutdown_function()
     * @see \register_shutdown_function
     * @return void
     */
    public function executeShutdown() {
        foreach($this->getWriter() as $writer) {
            /** @var Writer\AbstractWriter $writer */
            $writer->write();
        }
    }

    /**
     * @param bool $stopChain
     * @return void
     */
    public function registerErrorHandler($stopChain = true) {

       \set_error_handler(array($this, 'errorHandler'));

        // Contruct a default map of phpErrors to Zend_Log priorities.
        // Some of the errors are uncatchable, but are included for completeness
        $this->errorHandlerMapping = array(
            E_NOTICE => 'notice',
            E_USER_NOTICE => 'notice',
            E_WARNING => 'warn',
            E_CORE_WARNING => 'warn',
            E_USER_WARNING => 'warn',
            E_ERROR => 'err',
            E_USER_ERROR => 'err',
            E_CORE_ERROR => 'err',
            E_RECOVERABLE_ERROR => 'err',
            E_STRICT => 'debug',
        );

        $this->errorHandlerStopChain = (bool) $stopChain;

    }

    /**
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @param $errcontext
     * @return bool
     * @see \set_error_handler()
     */
    public function errorHandler($errno, $errstr, $errfile, $errline, $errcontext) {

        if (\error_reporting() && $errno) {
            $priority = (isset($this->errorHandlerMapping[$errno])) ? $this->errorHandlerMapping[$errno] : 'notice';
            $this->log($errstr, $priority);
        }

        // continue w/ native error handler?
        return $this->errorHandlerStopChain;
    }
}