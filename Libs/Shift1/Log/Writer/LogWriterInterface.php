<?php
namespace Shift1\Log\Writer;

interface LogWriterInterface {

    /**
     * @return void
     */
    public function write();

    /**
     * @return string
     */
    public function getLevel();

}