<?php
namespace Shift1\Log;

interface iLogger {

    public function addWriter(Writer\iLogWriter $writer);

    public function getWriter();

    public function log($msg, $errLevel);
  
}
