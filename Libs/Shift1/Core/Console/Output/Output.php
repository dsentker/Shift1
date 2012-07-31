<?php
namespace Shift1\Core\Console\Output;

class Output {

    protected $ln;

    public function __construct($ln) {
        $this->ln = $ln;
    }

    public function __toString() {
        return $this->ln . \PHP_EOL;
    }

}
