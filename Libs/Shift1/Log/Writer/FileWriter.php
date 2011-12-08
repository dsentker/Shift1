<?php
namespace Shift1\Log\Writer;


class FileWriter extends AbstractWriter {

    protected $file;

    public function __construct($file) {
        $this->file = $file;
    }

    public function write($msg) {
        \file_put_contents($this->file, $msg . \PHP_EOL, FILE_APPEND);
    }

}
