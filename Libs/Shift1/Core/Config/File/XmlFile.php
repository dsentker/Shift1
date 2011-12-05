<?php

namespace Shift1\Core\Config\File;;

class configFileXml extends AbstractConfigFile {


    public function toArray() {
        return $this->parseXml($this->getConfigFile());
    }

    protected function parseXml() {
        /**
         * @todo write this method.
         */
    }

}

?>
