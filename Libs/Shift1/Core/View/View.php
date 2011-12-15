<?php
namespace Shift1\Core\View;

use Shift1\Core\Exceptions\ViewException;

function testr($buffer) {
    $buffer = null;
    return $buffer;
}

class View extends AbstractView {

    /**
     * @var null|self
     */
    protected $wrapperView = null;

    /**
     * @var null|string
     */
    protected $wrapperSlot = null;

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

        $templatePath = $this->getViewPath() . $viewFile;

        if(!\file_exists($templatePath) && $throw) {
            throw new ViewException("View File {$viewFile} not found in {$this->getViewPath()}");
        }


        \ob_start('\Shift1\Core\View\testr');
        require $templatePath;
        return \ob_get_clean();

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

        // Copy file, path and isStrict, if not defined
        $viewFile = (null === $viewFile) ? $this->getViewFile() : $viewFile;
        $viewPath = (null === $viewPath) ? $this->getViewPath() : $viewPath;
        $strict   = (null === $strict)   ? $this->isStrict()    : $strict;

        $newSelf = new self($viewFile, null, $strict);
        $newSelf->setViewPath($viewPath, false);
        return $newSelf;
    }

    /**
	 * Calls ::getContent() and returns the content
	 *
	 * @access Public
	 * @return string
	 */
	public function render() {

        $content = $this->getContent();

        if($this->wrapperExists()) {
            $this->wrapperView->assign($this->wrapperSlot, $content);
            $content = $this->wrapperView->render();
        }

        return $content;

	}

    /**
     * @param View $view
     * @param string $slotName
     * @return void
     */
    public function wrappedBy(self $view, $slotName = 'content') {
        $this->wrapperView = $view;
        $this->wrapperSlot = $slotName;
    }

    /**
     * @return bool
     */
    protected function wrapperExists() {
        return $this->wrapperView instanceof iView;
    }


}