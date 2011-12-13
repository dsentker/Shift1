<?php
namespace Shift1\Core\View;

use Shift1\Core\Shift1Object;
use Shift1\Core\InternalFilePath;

abstract class abstractView extends Shift1Object implements iView {

    const VAR_KEY_PREFIX = '__';

    /**
     * @var array
     */
    protected $viewVars = array();

    /**
     * @var string
     */
    protected $viewFile;

    /**
     * @var string
     */
    protected $viewPath;

    /**
     * @var bool
     */
    protected $strict;

    /**
     * @var null|self
     */
    protected $wrapperView = null;

    /**
     * @var null|string
     */
    protected $wrapperSlot = null;


    /**
     * @static
     * @param string $viewFile
     * @return self
     */
    public static function instance($viewFile) {
        return new self($viewFile);
    }

    /**
     * @param null|string $viewFile
     * @param null|string $viewPath
     * @param null|bool $strict
     */
    public function __construct($viewFile = null, $viewPath = null, $strict = null) {
        $config = $this->getApp()->getConfig();
        $this->setViewFile($viewFile);

        if(null === $viewPath)
            $viewPath = $config->filesystem->defaultViewFilePath;
        $this->setViewPath($viewPath);

        if(null === $strict)
            $strict = $config->view->strict;
        $this->setStrict($strict);
	}

    /**
     * @param string $viewFile
     * @return self
     */
    public function setViewFile($viewFile) {

        if(\strpos($viewFile, '.') === false) {
            $viewFile .= '.' . $this->getApp()->getConfig()->filesystem->defaultViewFileExtension;
        }

        $this->viewFile = $viewFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getViewFile() {
        return $this->viewFile;
    }

    /**
     * @param string $path
     * @param bool $adjustPath
     * @return void
     */
    public function setViewPath($path, $adjustPath = true) {

        if(!($path instanceof InternalFilePath) && $adjustPath) {
            $path = new InternalFilePath($path);
        }
        $this->viewPath = $path . \DIRECTORY_SEPARATOR;
	}

    /**
     * @return string
     */
    public function getViewPath() {
        return $this->viewPath;
    }

    /**
     * @param string $varKey
     * @param mixed $varValue
     * @param bool $overwrite
     * @return self
     */
	public function assign($varKey, $varValue, $overwrite = true) {
        if(!($this->varKeyExists($varKey) && $overwrite === false)) {
            $this->viewVars[self::VAR_KEY_PREFIX . $varKey] = $varValue;
        }
        return $this;
	}

    /**
     * @param array $vars
     * @param bool $overwrite
     * @return self
     */
	public function assignArray(array $vars, $overwrite = false) {
        foreach($vars as $key => $var) {
            $this->assign($key, $var, $overwrite);
        }
        return $this;
	}

    /**
     * @param $varKey
     * @return mixed|null
     */
	public function get($varKey) {
        if(isset($this->viewVars[self::VAR_KEY_PREFIX . $varKey])) {
            return $this->viewVars[self::VAR_KEY_PREFIX . $varKey];
        } else {
            if($this->isStrict()) {
                \trigger_error(sprintf('View variable "%s" does not exist for ' . $this->getViewFile(), $varKey), E_USER_NOTICE);
            }
            return null;
        }
	}

    /**
     * @return array
     */
    public function getViewVars() {
        return $this->viewVars;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function varKeyExists($key) {
        return isset($this->viewVars[self::VAR_KEY_PREFIX . $key]);
    }

    /**
     * @return void
     */
    public function clearVars() {
        $this->viewVars = array();
    }

    /**
     * @param string $key
     * @param mixed $val
     * @return void
     */
	public function __set($key, $val) {
        $this->assign($key, $val);
	}

    /**
     * @param string $var
     * @return mixed|null
     */
	public function __get($var) {
        return $this->get($var);
	}

    /**
     * @return string
     */
	public function __toString() {
        return $this->getContent(false);
	}

    /**
     * @param bool $flag
     * @return void
     */
    public function setStrict($flag = true) {
        $this->strict = $flag;
    }

    /**
     * @return bool
     */
    public function isStrict() {
        return $this->strict;
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
     * @param self $view
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