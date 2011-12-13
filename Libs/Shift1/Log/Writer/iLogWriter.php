<?php
namespace Shift1\Log\Writer;

interface iLogWriter {

    /**
     * @return void
     */
    public function write();

    /**
     * @return string
     */
    public function getLevel();

}