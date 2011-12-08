<?php
namespace Shift1\Log\Writer;

interface iLogWriter {

    /**
     * @param string $msg
     * @return void
     */
    public function write($msg);

    /**
     * @return string
     */
    public function getLevel();

}
