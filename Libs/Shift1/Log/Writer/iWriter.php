<?php
namespace Shift1\Log\Writer;

interface iLogWriter {

    public function write($msg);

    /**
     * @return int
     */
    public function getPriority();

}
