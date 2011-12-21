<?php
namespace Shift1\Core\View;

use Shift1\Core\Exceptions\ViewException;
use Shift1\Core\InternalFilePath;

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
     * @throws \Shift1\Core\Exceptions\ViewException if ::throw is TRUE
     * @return string
     */
    public function getContent() {

        $viewFile = $this->getViewFile();

        if(empty($viewFile)) {
            if($this->isThrowingExceptions()) {
                throw new ViewException('No View file given!');
            } else {
                die('No View file given!');
            }

        }

        $viewFile = new InternalFilePath($viewFile);

        if(!$viewFile->exists()) {
            if($this->isThrowingExceptions()) {
                throw new ViewException("View File {$viewFile} not found!");
            } else {
                die("Exit: View File {$viewFile} not found");
            }
        }

        \ob_start(null);
        require $viewFile->getAbsolutePath();
        return \ob_get_clean();

    }

    /**
     * @param string|Shift1\Core\InternalFilePath $file
     * @return bool
     */
    public function fileExists($file) {
        if(!($file instanceof InternalFilePath)) {
            $file = $this->completeViewFilename($file);
            $file = new InternalFilePath($file);
        }
        return $file->exists();
    }

    /**
     * Creates a new view file. This method is callable
     * from the view file and is useful when a parent
     * wrapper is defined.

     * @param null|string $viewFile
     * @param bool $useDefaultViewFilePath
     * @return View
     */
    public function newSelf($viewFile = null, $useDefaultViewFilePath = true) {

        // Copy file, path and isStrict, if not defined
        $viewFile = (null === $viewFile) ? $this->getViewFile() : $viewFile;

        $newSelf = new self($viewFile, $this->isStrict(), $useDefaultViewFilePath);

        if($this->isThrowingExceptions()) {
            $newSelf->enableExceptions();
        } else {
            $newSelf->disableExceptions();
        }

        return $newSelf;

    }

    /**
     * @return self
     */
    public function __clone() {
        return $this->newSelf();
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