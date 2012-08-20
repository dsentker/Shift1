<?php
namespace Shift1\Core\Parser;

use Symfony\Component\Yaml\Parser as Symfony2YamlParser;

class YamlParser extends Symfony2YamlParser {

    const NODE_EXTEND_KEY = '@extends';

    protected $parsingResult = array();

    public function parse($value) {
        $res = parent::parse($value);

        $this->parsingResult = $res;

        \array_walk_recursive($res, function(&$item, $key) {
           if(self::NODE_EXTEND_KEY == $key) {

           }
        });

        $this->injectBlocksRecursive($res);

        return $this->parsingResult;
    }

    protected function injectBlocksRecursive(array $root, $lastParentKey = '') {
        $result = array();
        foreach($root as $nodeKey => $nodeVal) {

            if(self::NODE_EXTEND_KEY == $nodeKey) {
                #echo "Hell yes!";
                $sourcePath = \explode('.', $nodeVal);
                $requested = $this->getValFromSourcePath($sourcePath);
                die(Print_r($requested));
            } elseif(\is_array($nodeVal)) {
                $lastParentKey = $nodeKey;
                $this->injectBlocksRecursive($nodeVal, $lastParentKey);
            }
        }
    }

    protected function getValFromSourcePath(array $sourcePath, array $root = null) {


        $root = (null === $root) ? $this->parsingResult : $root;
        $item = \array_shift($sourcePath);

        if(isset($root[$item])) {
            $root = $root[$item];
            if(empty($sourcePath)) {
                return $root;
            } else {
                return $this->getValFromSourcePath($sourcePath, $root);
            }

        }

    }



}
