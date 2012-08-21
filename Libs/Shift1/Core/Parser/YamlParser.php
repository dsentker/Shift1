<?php
namespace Shift1\Core\Parser;

use Symfony\Component\Yaml\Parser as Symfony2YamlParser;

class YamlParser extends Symfony2YamlParser {

    const NODE_EXTEND_KEY = '<';

    protected $parsingResult = array();

    public function parse($value) {
        $this->parsingResult = parent::parse($value);
        return $this->fetchExtender($this->parsingResult);
    }

    protected function fetchExtender(array $result) {
        $return = array();
        foreach ($result as $key => $value) {

            $extender = '';
            if (strpos($key, '<') !== false) {
                // Extender found
                $parts = \explode('<', $key);
                $key = \trim($parts[0]);
                $extender = \trim($parts[1]);
            }

            if(is_array($value) && $extender !== '') {
                $extend = $this->getValueFromPath($extender, $this->parsingResult);

                if(!is_array($extend)) {
                    die($extender . ' must point to an array, Given ' . gettype($extend) . ' (' . var_export($extend, 1) . ')');
                }

                $value = $this->mergeArraysRecursive($extend, $value);
            }

            if (is_array($value)) {
                $value = $this->fetchExtender($value);
            }

            $return[$key] = $value;
        }
        return $return;
    }

    /**
     * Delivers the value from array $haystack, fetched by an path like foo.bar.baz
     *
     * @param string $path
     * @param array $haystack
     * @return array
     */
    protected function getValueFromPath($path, array $haystack) {

        if(\strpos($path, '.') === false) {
            // end of chain
            $key = $path;
            $remainingPath = '';
        } else {
            $pathParts = \explode('.', $path);
            $key = \array_shift($pathParts);
            $remainingPath = \implode('.', $pathParts);
        }

        if(!isset($haystack[$key])) {
            die("Array key not found: " . $key);
        }

        if(empty($remainingPath)) {
            return $haystack[$key];
        } else {
            // There is still something to fetch
            return $this->getValueFromPath($remainingPath, $haystack[$key]);
        }

    }

    /**
     * Note that php delivers a function named array_merge_recursive, which does
     * not work as needed: The native function does not overwrite a "final" array key,
     * but creates an array to preserve both values.
     * Thanks to andyidol at gmail dot com via php.net/array_merge_recursive#102379
     *
     * @param array $arr1
     * @param array $arr2
     * @return array
     */
    public function mergeArraysRecursive(array $arr1, array $arr2) {

        foreach($arr2 as $key => $value) {
            if(\array_key_exists($key, $arr1) && is_array($value))
                $arr1[$key] = $this->mergeArraysRecursive($arr1[$key], $arr2[$key]);
            else
                $arr1[$key] = $value;
        }
        return $arr1;

}



}
