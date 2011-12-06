<?php
namespace Shift1\Core;

class InternalFilePath {

    protected $path;

    public function __construct($path, $adjustSeparator = true) {

        if($adjustSeparator) {
            $path = \strtr($path, array(
                '/' => \DIRECTORY_SEPARATOR,
                '\\' => \DIRECTORY_SEPARATOR));
        }

        $this->path = $path;
    }

    public function getAbsolutePath() {
        return BASEPATH . \DIRECTORY_SEPARATOR . $this->path;
    }

    public function __toString() {
        return $this->getAbsolutePath();
    }

}
?>