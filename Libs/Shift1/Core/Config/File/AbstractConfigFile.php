<?php
namespace Shift1\Core\Config\File;

use Shift1\Core\Exceptions\FileNotFoundException;

abstract class AbstractConfigFile implements iConfigFile {

    protected $configFile;

    public function __construct($configFile) {
        if(!\file_exists($configFile)) {
            throw new FileNotFoundException($configFile);
        }
        $this->setConfigFile($configFile);
    }

    public function setConfigFile($configFile) {
        $this->configFile = $configFile;
    }

    public function getConfigFile() {
        return $this->configFile;
    }

    public function toArrayObject($arrItems = null) {

        $arrObjContents = array();

        $arr = \is_null($arrItems) ? $this->toArray() : $arrItems;

        foreach($arr as $key => $value) {
            $arrObjContents[$key] = (\is_array($value)) ? $this->toArrayObject($value) : $value;
        }

        return new \ArrayObject($arrObjContents, \ArrayObject::ARRAY_AS_PROPS);
    }

}

?>