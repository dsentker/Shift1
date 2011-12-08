<?php
namespace Shift1\Log;

interface iLog {

    const ALL = 1024;

    const DEBUG = 256;
    const STATUS = 128;
    const INFO = 64;
    const WARNING = 32;
    const ERROR = 4;

    public function addWriter(Writer\iLogWriter $writer);

    public function getWriter();

    public function log($msg, $errLevel);
  
}
