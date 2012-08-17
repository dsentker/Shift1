<?php
namespace Shift1\Core\Config\Manager;

interface ConfigManagerInterface {

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