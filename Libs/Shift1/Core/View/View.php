<?php
namespace Shift1\Core\View;

use Shift1\Core\Exceptions\ViewException;

class View extends AbstractView {

    /**
     * @throws \Shift1\Core\Exceptions\ViewException
     * @param bool $throw
     * @return string
     */
    public function getContent($throw = true) {

        $viewFile = $this->getViewFile();

        if(empty($viewFile) && $throw) {
            throw new ViewException('No View File given!');
        }

        $path = $this->getViewPath() . $viewFile;

        if(!\file_exists($path) && $throw)
            throw new ViewException("View File {$viewFile} not found in {$this->getViewPath()}");

        \ob_start();
            require $path;
            $c = \ob_get_contents();
        \ob_get_clean();

        return $c;
    }

    /**
     * Creates a new view file. This method is callable
     * from the view file and is useful when a parent
     * wrapper is defined.
     *
     * @param null|string $viewFile
     * @param null|string $viewPath
     * @param null|bool $strict
     * @return abstractView
     */
    public function newSelf($viewFile = null, $viewPath = null, $strict = null) {
        $viewFile = (null === $viewFile) ? $this->getViewFile() : $viewFile;
        $viewPath = (null === $viewPath) ? $this->getViewPath() : $viewPath;
        $strict   = (null === $strict)   ? $this->isStrict()    : $strict;
        $newSelf = new self($viewFile, null, $strict);
        $newSelf->setViewPath($viewPath, false);
        return $newSelf;
    }

}