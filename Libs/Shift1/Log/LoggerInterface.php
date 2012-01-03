<?php
namespace Shift1\Log;

interface LoggerInterface {

    /**
     * @abstract
     * @param Writer\LogWriterInterface $writer
     * @return void
     */
    public function addWriter(Writer\LogWriterInterface $writer);

    /**
     * @abstract
     * @return Writer\LogWriterInterface
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