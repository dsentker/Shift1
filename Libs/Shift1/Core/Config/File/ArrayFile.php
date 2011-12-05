<?php

namespace Shift1\Core\Config\File;


class ArrayFile extends AbstractConfigFile {


    public function toArray() {
        return include $this->getConfigFile();
    }

}

?>
