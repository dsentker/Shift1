<?php
namespace Shift1\Log;

class Logger extends AbstractLog {

    /**
     * @var array
     */
    protected $writer = array();

    /**
     * @var string
     */
    protected $format = "%timestamp%  %levelname%(%errorlevel%): %message%";

    public function setFormat($format) {
        $this->format = $format;
    }

    public function getFormat() {
        return $this->format;
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
        }

        $timestamp = \date('Y-m-d H:i:s');
        
        $translate = array(
          '%message%' => $msg,
          '%timestamp%' => $timestamp,
          '%errorlevel%' => $errLevel,
          '%levelname%' => $errLevelName,
        );
        $message = \strtr($this->getFormat(), $translate);

        foreach($this->getWriter() as $writer) {

            /** @var Writer\iLogWriter $writer */
            if((int) $errLevel <= $writer->getLevel()) {
                $writer->write($message);
            }

        }
    }

}