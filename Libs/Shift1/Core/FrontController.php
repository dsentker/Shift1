<?php
namespace Shift1\Core;

use Shift1\Core\Service\ServiceContainerInterface;
use Shift1\Core\Config\Manager\ConfigManagerInterface;
use Shift1\Core\Controller\Factory\ControllerFactory;
use Shift1\Core\Request\RequestInterface;
use Shift1\Core\Router\AbstractRouter;

class FrontController {

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
     * @var null|Shift1\Core\Request\iRequest
     */
    protected $request;

    /**
     * @var null|Shift1\Core\Router\AbstractRouter
     */
    protected $router;

    /**
     * @static
     * @return FrontController
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
     * @throws FrontControllerException
     * @param \Shift1\Core\Request\RequestInterface $request
     * @return mixed
     */
    public function handle(RequestInterface $request) {

        $uri = $request->getProjectUri($this->getConfig()->route->appWebRoot);
        $data = $this->getRouter()->resolveUri($uri);

        if($this->validateRequestResult($data) === false) {
            throw new FrontControllerException('No valid Request result given: ' . \PHP_EOL . \var_export($data, 1));
        }

        $response = ControllerFactory::createController($data['_controller'], $data['_action'], $data);
        return $response->sendToClient();
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function validateRequestResult(array $data) {
        return ( isset($data['_controller']) && isset($data['_action']) );
    }

        /**
     * @param Config\Manager\ConfigManagerInterface $config
     * @return void
     */
    public function setConfig(ConfigManagerInterface $config) {
        $this->configManager = $config;
    }

    /**
     * @return null|Config\Manager\ConfigManagerInterface
     */
    public function getConfig() {
        return $this->configManager;
    }

    /**
     * @param Service\ServiceContainerInterface $serviceContainer
     * @return void
     */
    public function setServiceContainer(ServiceContainerInterface $serviceContainer) {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @throws \RuntimeException
     * @return Service\ServiceContainerInterface
     */
    public function getServiceContainer() {
        if(!($this->serviceContainer instanceof Service\ServiceContainerInterface)) {
            throw new \RuntimeException('No service container defined');
        }
        return $this->serviceContainer;
    }

    /**
     * @return null|Shift1\Core\Request\RequestInterface
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * @param Router\AbstractRouter $router
     * @return void
     */
    public function setRouter(AbstractRouter $router) {
        $this->router = $router;
    }

    public function getRouter() {
        return $this->router;
    }
}