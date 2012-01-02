<?php
namespace Shift1\Core\Controller;

use Shift1\Core\Exceptions\ControllerException;

abstract class AbstractController implements ControllerInterface  {

    /**
     * @var string
     */
    static $actionDefault = 'index';

    /**
     * @var string
     */
    static $actionNotFound = 'notFound';

    /**
     * @var array
     */
    protected $params = array();

    /**
     * @param array $params
     */
    public function __construct(array $params = array()) {
        $this->params = $params;
    }

    /**
     * @param array $params
     * @return void
     */
    public function setParams(array $params) {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * @param string $paramIdentifier
     * @param mixed $defaultReturn
     * @return mixed
     */
    protected function getParam($paramIdentifier, $defaultReturn = null) {
        return ($this->hasParam($paramIdentifier)) ? $this->params[$paramIdentifier] : $defaultReturn;
    }

    /**
     * @param string $paramIdentifier
     * @return bool
     */
    protected function hasParam($paramIdentifier) {
        return isset($this->params[$paramIdentifier]);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function addParam($key, $value) {
        $this->params[$key] = $value;
    }

    /**
     * @return void
     */
    public function init() {
        
    }

    /**
     * @static
     * @return string
     */
    public static function getDefaultActionName() {
        return self::$actionDefault;
    }

    /**
     * @static
     * @return string
     */
    public static function getNotFoundActionName() {
        return self::$actionNotFound;
    }

    /**
     * @return string
     */
    public function getControllerName() {
        $fqClassname = \get_class($this);
        $parts = \explode('\\', $fqClassname);
        return \array_pop($parts);
    }

}