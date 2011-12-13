<?php
namespace Shift1\Log;

interface iLogger {

    /**
     * @abstract
     * @param Writer\iLogWriter $writer
     * @return void
     */
    public function addWriter(Writer\iLogWriter $writer);

    /**
     * @abstract
     * @return Writer\iLogWriter
     */
    public function getWriter();

    /**
     * @abstract
     * @param string $msg
     * @param string $errLevel
     * @return void
     */
    public function log($msg, $errLevel);
  
}