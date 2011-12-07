<?php
namespace Shift1\Log;

interface iLog {

    const ALL = 1024;

    const DEBUG = 256;
    const STATUS = 128;
    const INFO = 64;
    const WARNING = 32;
    const ERROR = 4;

    public function addLogger();

    public function getLogger();

    public function write($msg, $errLevel);

}
