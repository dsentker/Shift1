<?php
namespace Shift1\Log;

interface iLog {

    

    public function addWriter(Writer\iLogWriter $writer);

    public function getWriter();

    public function log($msg, $errLevel);
  
}
