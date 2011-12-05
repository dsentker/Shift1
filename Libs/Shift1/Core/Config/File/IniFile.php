<?php

namespace Shift1\Core\Config\File;

use Shift1\Core\Parser\IniParser;


class IniFile extends AbstractConfigFile {

    protected $processIniSections;

    public function __construct($configFile, $processIniSections = true) {
        $this->processIniSections = (bool) $processIniSections;
        parent::__construct($configFile);
    }

    public function toArray() {
        return $this->parseIniFile();
    }

    protected function parseIniFile($parseConstants = true) {
        $ini = IniParser::parse($this->getConfigFile(), $this->processIniSections);

        if($parseConstants) {
           /**
            * Uses a workaround to parse constants in ini File
            * @see http://php.net/manual/de/function.parse-ini-File.php#76082
            */
            $buf = \get_defined_constants(true);
            $consts = $buf['user'] + $buf['Core'];
            \array_walk_recursive($ini, array($this, 'replaceIniConstants'), $consts);
        }

        return $ini;
    }

    protected function replaceIniConstants(&$item, $key, $consts) {
        $item = \str_replace(\array_keys($consts), \array_values($consts), $item);
    }

}

?>
