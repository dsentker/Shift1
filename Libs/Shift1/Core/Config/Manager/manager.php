<?php
namespace Shift1\Core\Config\Manager;

use Shift1\Core\Config\File\iConfigFile;

class Manager extends AbstractManager {

    /**
     * @param \Shift1\Core\Config\File\iConfigFile $configFile
     * @param string $environment
     */
    public function __construct(iConfigFile $configFile, $environment) {
        $configData = $configFile->toArrayObject();
        parent::__construct($configData[$environment]);
    }

}
?>