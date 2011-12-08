<?php
namespace Shift1\Log;

class Log implements iLog {

    protected $writer = array();

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
     * @param integer $errLevel
     * @return void
     */
    public function log($msg, $errLevel) {
        foreach($this->getWriter() as $writer) {
            /** @var Writer\iLogWriter $writer */
            if((int) $errLevel > $writer->getPriority())
            $writer->write($msg);
        }
    }


}
