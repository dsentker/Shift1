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
                '\\' => \DIRECTORY_SEPARATOR
            ));
        }

        $this->path = $path;
    }

    /**
     * @return bool
     */
    public function exists() {
        return (\file_exists($this->getAbsolutePath()) && \is_file($this->getAbsolutePath()));
    }

    /**
     * @return string
     */
    public function getAbsolutePath() {
        return BASEPATH . \DIRECTORY_SEPARATOR . $this->path;
    }

    public function getPath() {
        return $this->path;
    }

    public function getAbsolutePathAsArray() {
        return \explode(\DIRECTORY_SEPARATOR, $this->getAbsolutePath());
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->getAbsolutePath();
    }

}