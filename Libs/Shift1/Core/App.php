<?php
namespace Shift1\Core;

use Shift1\Core\Service\iServiceContainer;
use Shift1\Core\FrontController\iFrontController;
use Shift1\Core\Config\Manager\iConfigManager;
use Shift1\Core\Request\iRequest;
use Shift1\Core\Exceptions\FrontControllerException;


final class App {

    /**
     * @var null|self
     */
    static private $instance = null;

    /**
     * @var null|\Shift1\Core\Config\Manager\iConfigManager
     */
    protected $configManager;

    /**
     * @var null|\Shift1\Core\Service\iServiceContainer
     */
    protected $serviceContainer;

    /**
     * @var null|Shift1\Core\FrontController\iFrontController
     */
    protected $frontController;

    /**
     * @var null|Shift1\Core\Request\iRequest
     */
    protected $request;

    /**
     * @static
     * @return self
     */
    static public function getInstance() {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * @private
     */
    private function __construct(){}

    /**
     * @private
     * @return void
     */
    private function __clone(){}

    /**
     * @param Config\Manager\iConfigManager $config
     * @return void
     */
    public function setConfig(iConfigManager $config) {
        $this->configManager = $config;
    }

    /**
     * @return null|Config\Manager\iConfigManager
     */
    public function getConfig() {
        return $this->configManager;
    }

    /**
     * @param Service\iServiceContainer $serviceContainer
     * @return void
     */
    public function setServiceContainer(iServiceContainer $serviceContainer) {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @return null|Service\iServiceContainer
     */
    public function getServiceContainer() {
        return $this->serviceContainer;
    }

    /**
     * @param FrontController\iFrontController $frontController
     * @return void
     */
    public function setFrontController(iFrontController $frontController) {
        $this->frontController = $frontController;
    }

    /**
     * @return null|Shift1\Core\FrontController\iFrontController
     */
    public function getFrontController() {
        return $this->frontController;
    }

    /**
     * @param Request\iRequest $request
     * @return void
     */
    public function setRequest(iRequest $request) {
        $this->request = $request;
    }

    /**
     * @return null|Shift1\Core\Request\iRequest
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * @throws Exceptions\FrontControllerException
     * @return string
     */
    public function execute() {
        if(empty($this->frontController)) {
            throw new FrontControllerException('Could not execute App - No Frontcontroller defined');
        }
        
        $this->getFrontController()->run();
    }
}