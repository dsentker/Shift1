<?php
namespace Shift1\Core\Config\File;

use Shift1\Core\Exceptions\FileNotFoundException;

abstract class AbstractConfigFile implements ConfigFileInterface {

    /**
     * @var string
     */
    protected $configFile;


    /**
     * @param string $configFile
     */
    public function __construct($configFile) {
        $this->setConfigFile($configFile);
    }

    /**
     * @throws \Shift1\Core\Exceptions\FileNotFoundException
     * @param string $configFile
     * @return void
     */
    public function setConfigFile($configFile) {
        if(!\file_exists($configFile)) {
            throw new FileNotFoundException($configFile);
        }
        $this->configFile = $configFile;
    }

    /**
     * @return string
     */
    public function getConfigFile() {
        return $this->configFile;
    }

    /**
     * Acts recursive.
     *
     * @param array|null $arrItems
     * @return \ArrayObject
     */
    public function toArrayObject(array $arrItems = null) {

        $arrObjContents = array();

        $arr = \is_null($arrItems) ? $this->toArray() : $arrItems;

        foreach($arr as $key => $value) {
            $arrObjContents[$key] = (\is_array($value)) ? $this->toArrayObject($value) : $value;
        }

        return new \ArrayObject($arrObjContents, \ArrayObject::ARRAY_AS_PROPS);
    }
}