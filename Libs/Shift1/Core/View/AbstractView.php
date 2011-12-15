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
     * @static
     * @param string $viewFile
     * @return self
     */
    public static function instance($viewFile) {
        return new static($viewFile);
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
     * @return array
     */
    public function getViewKeys() {
        return \array_keys($this->getViewVars());
    }

    /**
     * @return array
     */
    public function getViewValues() {
        return \array_values($this->getViewVars());
    }

    /**
     * @param string $key
     * @return bool
     */
    public function varKeyExists($key) {
        return isset($this->viewVars[self::VAR_KEY_PREFIX . $key]);
    }

    /**
     * Just an alias to varKeyExists()
     * @param string $key
     * @return bool
     */
    public function has($key) {
        return $this->varKeyExists($key);
    }

    /**
     * Just an alias to varKeyExists()
     * @param string $key
     * @return bool
     */
    public function __isset($key) {
        return $this->varKeyExists($key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function removeVar($key) {
        if($this->has($key)) {
            unset($this->viewVars[$key]);
            return true;
        }
        return false;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function __unset($key) {
        return $this->remove($key);
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

}