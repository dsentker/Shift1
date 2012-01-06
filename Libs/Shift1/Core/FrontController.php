<?php
namespace Shift1\Core;

use Shift1\Core\Service\Container\ServiceContainerInterface;
use Shift1\Core\Config\Manager\ConfigManagerInterface;
use Shift1\Core\Controller\Factory\ControllerFactory;
use Shift1\Core\Exceptions\FrontControllerException;
use Shift1\Core\Request\RequestInterface;
use Shift1\Core\Response\ResponseInterface;
use Shift1\Core\Router\AbstractRouter;

class FrontController {

    /**
     * @var null|self
     */
    static private $instance = null;


    /**
     * @var null|\Shift1\Core\Config\Manager\ConfigManagerInterface
     */
    protected $configManager;

    /**
     * @var null|\Shift1\Core\Service\ServiceContainerInterface
     */
    protected $serviceContainer;

    /**
     * @var null|\Shift1\Core\Request\RequestInterface
     */
    protected $request;

    /**
     * @var null|\Shift1\Core\Router\AbstractRouter
     */
    protected $router;

    /**
     * The only purpose to clone a front controller
     * is a internal request for HMVC actions. So
     * the request must be resetted.
     * 
     * @return void
     */
    public function __clone() {
        $this->request = null;
    }

    /**
     * @throws Exceptions\FrontControllerException
     * @param Request\RequestInterface $request
     * @return void|string
     */
    public function handle(RequestInterface $request) {

        $this->request = $request;

        $uri = $request->getProjectUri($this->getConfig()->route->appWebRoot);
        $data = $this->getRouter()->resolveUri($uri);

        if($this->validateRequestResult($data) === false) {
            throw new FrontControllerException('No valid request result given: ' . \var_export($data, 1));
        }

        $controllerAggregate = ControllerFactory::createController($this->getConfig()->controller, $data['_controller'], $data['_action'], $data);
        $controllerAggregate->getController()->setFrontController($this);
        $controllerAggregate->getController()->init();

        $response = $controllerAggregate->run();
        
        if(!($response instanceof ResponseInterface)) {
            throw new FrontControllerException('No response valid given: ' . \var_export($response, 1));
        }

        if($this->getRequest()->getIsInternal()) {
            return $response->getContent();
        }
        $response->sendToClient();
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
     * @return null|\Shift1\Core\Request\RequestInterface
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

    /**
     * @return null|\Shift1\Core\Router\AbstractRouter
     */
    public function getRouter() {
        return $this->router;
    }
}