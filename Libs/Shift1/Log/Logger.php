<?php
namespace Shift1\Log;

use Shift1\Core\Exceptions\LoggerException;

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

    public function __construct() {
        
    }

    public function setFormat($format) {
        $this->format = $format;
    }

    public function getFormat() {
        return $this->format;
    }

    public function setTimestampFormat($format) {
        $this->timestampFormat = $format;
    }

    public function getTimestampFormat() {
        return $this->timestampFormat;
    }

    /**
     * @param Writer\iLogWriter $writer
     * @return void
     */
    public function addWriter(Writer\iLogWriter $writer) {
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
     * @return void
     */
    public function log($msg, $level = 'debug') {

        if(\is_int($level)) {
            $errLevel = $level;
            $errLevelName = $this->getLevelName($level);
        } elseif(\is_string($level)) {
            $errLevel = $this->getLevel($level);
            $errLevelName = $level;
        } else {
            throw new LoggerException('Can\'t identify log level "' . $level . '"');
        }

        $event = $this->createEvent($msg, $errLevel, $errLevelName);

        foreach($this->getWriter() as $writer) {
            /** @var Writer\AbstractWriter $writer */

            if((int) $errLevel <= $this->getLevel($writer->getLevel())) {
                $writer->addEvent($event);
            }

        }

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

    public function __destruct() {
        foreach($this->getWriter() as $writer) {
            /** @var Writer\AbstractWriter $writer */

            $writer->write();
        }
    }
}