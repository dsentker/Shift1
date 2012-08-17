<?php
namespace Shift1\Core\Config\File;;

class configFileXml extends AbstractConfigFile {

    /**
     * @return array
     */
    public function toArray() {
        return $this->parseXml($this->getConfigFile());
    }

    /**
     * @return void
     */
    protected function parseXml() {
        /*
         * @todo write this method.
         */
    }

}