<?php
namespace Shift1\Core\Config\File;

interface ConfigFileInterface {

    /**
     * @abstract
     * @return array
     */
    public function toArray();

    /**
     * @abstract
     * @return \ArrayObject
     */
    public function toArrayObject();

}