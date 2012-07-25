<?php
namespace Shift1\Core\View\TemplateAnnotationReader;

use Shift1\Core\Exceptions\AnnotationReaderException;

class TemplateAnnotationReader implements TemplateAnnotationReaderInterface {

    const DOCBLOCK_BEGIN = '/**';
    const DOCBLOCK_BODY  = '*';
    const DOCBLOCK_END   = '*/';

    const ANNOTATION_MARKER = '@';

    const REGEX_KEY = '@\w+';
    const REGEX_PARAMS ='"([^"]*)"|\'([^\']*)\''; // "([^"]*)"|'([^']*)'

    /** @var string */
    protected $file;

    /**
     * @var array
     */
    protected $elements = array();

    public function __clone() {
        $this->elements = array();
    }

    public function parse($file) {

        $fileLines = \file($file, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);

        foreach($fileLines as $lineNr => $line) {
            $line = trim($line);

            if(strpos($line, self::DOCBLOCK_BEGIN) === 0) {
                while($line != self::DOCBLOCK_END && isset($fileLines[$lineNr+1])) {

                    if(strpos($line, self::DOCBLOCK_BODY) === 0) {
                        $key = $this->getMatch(self::REGEX_KEY, $line);
                        $val = $this->getMatch(self::REGEX_PARAMS, $line);
                        if(false !== $key[0][0] && !empty($key[0][0])) {
                            $key = $this->removeAnnotationMarker($key[0][0]);
                            if(false !== $val) {
                                $this->elements[$key] = $this->removeQuotations($val[0]);
                            } else {
                                $this->elements[$key] = null;
                            }
                        }

                    }
                    $line = trim($fileLines[++$lineNr]);
                }
                return true;
            }
        }
        return false;

    }

    public function getResult() {
        return $this->elements;
    }

    public function hasAnnotation($key) {
        return \array_key_exists($key, $this->elements);
    }

    public function hasAnnotationParameter($key) {
        return ($this->hasAnnotation($key) && \is_array($this->elements[$key]));
    }

    public function hasAnnotationParameterCount($key, $count, $mode = 'exact') {
        if(!$this->hasAnnotationParameter($key)) {
            return false;
        }
        $parameterCount = \count($this->getAnnotationParameter($key));
        $count = (int) $count;

        switch(\strtolower($mode)) {
            case 'exact':
                return ($count === $parameterCount);
                break;
            default:
            case 'min':
                return ($count <= $parameterCount);
                break;
            case 'max':
                return ($count >= $parameterCount);
                break;
        }
    }

    /**
     * @param $key
     * @return array|null
     */
    public function getAnnotationParameter($key) {
        return $this->elements[$key];
    }

    private function removeQuotations(array $values) {
        foreach($values as &$value) {
            $value = strtr($value, array(
                '"' => '',
                '\'' => '',
            ));
        }
        return $values;
    }

    private function removeAnnotationMarker($key) {
        return \str_replace(self::ANNOTATION_MARKER, '', $key);
    }

    private function getMatch($pattern, $subject) {
        preg_match_all('#' . $pattern . '#', $subject, $matches);
        if(empty($matches[0])) {
            return false;
        }

        return $matches;
    }

}