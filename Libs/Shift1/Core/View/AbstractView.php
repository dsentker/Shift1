<?php
namespace Shift1\Core\View;

use Shift1\Core\Shift1Object;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Exceptions\ViewException;

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
     * Wheter to throw an exception or not
     * @var bool
     */
    protected $throw = true;

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
     * @param null|bool $strict
     * @param bool $useDefaultViewFilePath
     */
    public function __construct($viewFile = null, $strict = null, $useDefaultViewFilePath = true) {

        if(!empty($viewFile)) {
            $this->setViewFile($viewFile, $useDefaultViewFilePath);
        }

        if(null === $strict) {
            $config = $this->getApp()->getConfig();
            $strict = $config->view->strict;
        }
        $this->setStrict($strict);
	}

    /**
     * @return void
     */
    public function disableExceptions() {
        $this->throw = false;
    }

    /**
     * @return void
     */
    public function enableExceptions() {
        $this->throw = true;
    }

    /**
     * @return bool
     */
    public function isThrowingExceptions() {
        return $this->throw;
    }

    /**
     * @param string $file
     * @return string
     */
    protected function completeViewFilename($file) {
        if(\strpos($file, '.') === false) {
            $file .= '.' . $this->getApp()->getConfig()->filesystem->defaultViewFileExtension;
        }
        return $file;
    }

    /**
     * @param string $viewFile
     * @param bool $useDefaultViewFilePath
     * @return self
     */
    public function setViewFile($viewFile, $useDefaultViewFilePath = true) {
        if($useDefaultViewFilePath === true) {
            $config = $this->getApp()->getConfig();
            $viewFile = $config->filesystem->defaultViewFolder . '/' . $viewFile;
        }

        $this->viewFile = $this->completeViewFilename($viewFile);

        return $this;
    }

    /**
     * @return string
     */
    public function getViewFile() {
        return $this->viewFile;
    }

    /**
     * @param string $varKey
     * @param mixed $varValue
     * @param bool $overwrite
     * @return self
     */
	public function assign($varKey, $varValue, $overwrite = true) {

        if(empty($varKey)) {
            throw new ViewException('Assignment failed: Empty keys are not allowed!');
        }

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
     * @param bool $prefixed Wether the keys get with internal prefix or not
     * @return array
     */
    public function getViewVars($prefixed = false) {

        if($prefixed) {
            return $this->viewVars;
        }

        $returnArray = array();
        foreach($this->viewVars as $key => $value) {
            $cleanKey = \str_replace(self::VAR_KEY_PREFIX, '', $key);
            $returnArray[$cleanKey] = $value;
        }
        return $returnArray;

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
     * Remove a VarKey. Returns true is the
     * deletion was successfull.
     * @param $key
     * @return bool
     */
    public function removeVar($key) {
        if(isset($this->viewVars[self::VAR_KEY_PREFIX . $key])) {
            unset($this->viewVars[self::VAR_KEY_PREFIX . $key]);
            return true;
        }
        return false;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function __unset($key) {
        return $this->removeVar($key);
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

        $wasThrowing = $this->isThrowingExceptions();

        // __toString always excepts a string, not an exception.
        $this->disableExceptions();

        $content = $this->getContent();
        if($wasThrowing) $this->enableExceptions();

        return $content;
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