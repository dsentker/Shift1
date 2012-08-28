<?php
namespace Shift1\Core\Config\Reader;

use Shift1\Core\Config\File\ConfigFileInterface;

class Reader extends AbstractReader {

    /**
     * @param \Shift1\Core\Config\File\ConfigFileInterface $configFile
     * @param string $environment
     */
    public function __construct(ConfigFileInterface $configFile, $environment) {
        $configData = $configFile->toArrayObject();
        parent::__construct($configData[$environment]);
    }

}