<?php
namespace Shift1\Core;

use Shift1\Core\Service\iServiceContainer;
use Shift1\Core\FrontController\iFrontController;
use Shift1\Core\Config\Manager\iConfigManager;
use Shift1\Core\Request\iRequest;
use Shift1\Core\Exceptions\FrontControllerException;


final class App {

    static private $instance = null;

    protected $configManager;

    /**
     * @var null|\Shift1\Core\Service\ServiceContainer
     */
    protected $serviceContainer;


    protected $frontController;


    protected $request;

    static public function getInstance() {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct(){}
    private function __clone(){}

    public function setConfig(iConfigManager $config) {
        $this->configManager = $config;
    }

    public function getConfig() {
        return $this->configManager;
    }

    public function setServiceContainer(iServiceContainer $serviceContainer) {
        $this->serviceContainer = $serviceContainer;
    }

    public function getServiceContainer() {
        return $this->serviceContainer;
    }

    public function setFrontController(iFrontController $frontController) {
        $this->frontController = $frontController;
    }

    public function getFrontController() {
        return $this->frontController;
    }

    public function setRequest(iRequest $request) {
        $this->request = $request;
    }

    public function getRequest() {
        return $this->request;
    }
    
    public function execute() {
        if(empty($this->frontController)) {
            throw new FrontControllerException('Could not execute App - No Frontcontroller defined');
        }
        
        $this->getFrontController()->run();
    }
}

?>