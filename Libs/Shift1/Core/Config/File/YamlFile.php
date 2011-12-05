<?php

namespace Shift1\Core\Config\File;

use \Symfony\Component\Yaml\Yaml;


class YamlFile extends AbstractConfigFile {

    public function toArray() {
        
        return Yaml::parse($this->getConfigFile());
        
    }

}

?>
