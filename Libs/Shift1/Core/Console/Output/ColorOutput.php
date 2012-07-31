<?php
namespace Shift1\Core\Console\Output;

class ColorOutput extends Output {

    const COLOR_INFO     = '1;33';

    const COLOR_WARN     = '1;31';

    const COLOR_SUCCCESS = '0;32';

    public function __toString() {
        $this->replaceTags();
        return parent::__toString();
    }

    protected function replaceTags() {
        $this->ln = \strtr($this->ln, array(
            '<warn>' => "\033[" . self::COLOR_WARN . "m",
            '</warn>' => "\033[0m",

            '<info>' => "\033[" . self::COLOR_INFO . "m",
            '</info>' => "\033[0m",

            '<success>' => "\033[" . self::COLOR_SUCCCESS . "m",
            '</success>' => "\033[0m",

        ));
    }

}
