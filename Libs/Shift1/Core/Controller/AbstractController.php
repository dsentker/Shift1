<?php
namespace Shift1\Core\Controller;

use Shift1\Core\Exceptions\ControllerException;
use Shift1\Core\FrontController;

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
    final public function __construct(array $params = array()) {
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


    /**
     * @return \Shift1\Core\View\View
     */
    public function getView() {
        return $this->view;
    }

    /**
     * @return \Shift1\Core\FrontController
     */
    public function getFrontController() {
        return FrontController::getInstance();
    }

    /**
     * @return \Shift1\Core\Service\ServiceContainerInterface
     */
    public function getContainer() {
        return $this->getFrontController()->getServiceContainer();
    }

    /**
     * Provides a symfony2-style getter for a
     * direct access to a specific service from
     * a controller
     * 
     * @param string $serviceName
     * @return mixed The Service Object
     */
    public function get($serviceName) {
        return $this->getContainer()->get($serviceName);
    }

    /**
     * @return \Shift1\Core\Config\Manager\ConfigManagerInterface
     */
    public function getConfig() {
       return $this->getFrontController()->getConfig();
    }

    /**
     * @return \Shift1\Core\Shift1\Core\Request\RequestInterface
     */
    public function getRequest() {
        return $this->getFrontController()->getRequest();
    }

    
}