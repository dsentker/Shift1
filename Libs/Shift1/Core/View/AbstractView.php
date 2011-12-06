<?php
namespace Shift1\Core\View;

use Shift1\Core\Shift1Object;
use Shift1\Core\Exceptions\ViewException;
use Shift1\Core\InternalFilePath;

class abstractView extends Shift1Object implements iView {

    const VAR_KEY_PREFIX = '__';

    protected $viewVars = array();

    protected $viewFile;

    protected $viewPath;

    protected $strict;

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

    public function setViewFile($viewFile) {

        if(\strpos($viewFile, '.') === false) {
            $viewFile .= '.' . $this->getApp()->getConfig()->filesystem->defaultViewFileExtension;
        }

        $this->viewFile = $viewFile;
        return $this;
    }

    public function getViewFile() {
        return $this->viewFile;
    }

    public function setViewPath($path) {

        if(!($path instanceof InternalFilePath)) {
            $path = new InternalFilePath($path);
        }

        $this->viewPath = $path . \DIRECTORY_SEPARATOR;
	}

    public function getViewPath() {
        return $this->viewPath;
    }

	public function assign($varKey, $varValue, $overwrite = true) {
        if(!($this->varKeyExists($varKey) && $overwrite === false)) {
            $this->viewVars[self::VAR_KEY_PREFIX . $varKey] = $varValue;
        }
        return $this;
	}

	public function assignArray(array $vars, $overwrite = false) {

        foreach($vars as $key => $var) {
            $this->assign($key, $var, $overwrite);
        }

        return $this;
	}


	public function get($varKey) {
        if(isset($this->viewVars[self::VAR_KEY_PREFIX . $varKey])) {
            return $this->viewVars[self::VAR_KEY_PREFIX . $varKey];
        } else {
            if($this->isStrict()) {
                \trigger_error(sprintf('View variable "%s" does not exist', $varKey), E_USER_NOTICE);
            }
            return null;
        }
        
	}

    public function getViewVars() {
        return $this->viewVars;
    }

    public function varKeyExists($key) {
        return isset($this->viewVars[self::VAR_KEY_PREFIX . $key]);
    }

    public function clearVars() {
        $this->viewVars = array();
    }

	public function __set($key, $val) {
        $this->assign($key, $val);
	}

	public function __get($var) {
        return $this->get($var);
	}

	public function __toString() {
        return $this->getContent(false);
	}

	protected function getContent($throw = true) {

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
	 * Calls ::getContent and returns the content
	 *
	 * @access Public
	 * @return string
	 */
	public function render() {
        return $this->getContent();
	}

}
?>