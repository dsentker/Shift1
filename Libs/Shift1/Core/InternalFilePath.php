<?php
namespace Shift1\Core;

class InternalFilePath {

    /**
     * @var string
     */
    protected $path;

    /**
     * @param $path
     * @param bool $adjustSeparator
     */
    public function __construct($path, $adjustSeparator = true) {

        if($adjustSeparator) {
            $path = \strtr($path, array(
                '/' => \DIRECTORY_SEPARATOR,
                '\\' => \DIRECTORY_SEPARATOR));
        }

        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getAbsolutePath() {
        return BASEPATH . \DIRECTORY_SEPARATOR . $this->path;
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->getAbsolutePath();
    }

}