<?php
namespace Shift1\Core\Config\Reader;

class ConfigReader extends \ArrayObject {


    public function get($path, &$context = null) {

        if(null === $context) {
            $context = $this;
        }

        $pieces = explode('.', $path);
        foreach ($pieces as $piece) {
            if ((!is_array($context) || !array_key_exists($piece, $context)) && !($context instanceof ConfigReader)) {
                // error occurred
                die(\sprintf("Config subkey '%s' not found (total config path was %s).", $piece, $path));
                return null;
            }
            $context = &$context[$piece];
        }
        return $context;





    }


}
