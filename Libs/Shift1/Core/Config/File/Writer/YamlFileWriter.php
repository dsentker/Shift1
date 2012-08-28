<?php
namespace Shift1\Core\Config\File\Writer;

use \Symfony\Component\Yaml\Yaml;

class YamlFileWriter implements ConfigWriterInterface  {

    protected $file;

    public function setPath($file) {
        $this->file = $file;
    }

    public function write(array $content) {
        $ymlData = Yaml::dump($content, 6);
        return \file_put_contents($this->file, $ymlData);
    }

}