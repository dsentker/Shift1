<?php
namespace Shift1\Core\Config\Reader;

interface ConfigReaderInterface {

    /**
     * @abstract
     * @param \ArrayObject $configData
     */
    function setConfigData(\ArrayObject $configData);

    /**
     * @abstract
     * @return \ArrayObject
     */
    function getConfigData();

}