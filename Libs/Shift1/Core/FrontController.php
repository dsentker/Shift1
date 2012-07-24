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
     * @var null|\Shift1\Core\Service\ServiceContainerInterface
     */
    protected $serviceContainer;

    /**
     * @throws Exceptions\FrontControllerException
     * @return void|string
     */
    public function execute() {

        $router  = $this->getServiceContainer()->get('shift1.router');

        /**
         * @var $router \Shift1\Core\Router\RouterInterface
         */

        $data = $router->resolve();

        if($this->validateRequestResult($data) === false) {
            throw new FrontControllerException('No valid request result given: ' . \var_export($data, 1));
        }

        $controllerFactory = $this->getServiceContainer()->get('shift1.controllerFactory');
        /** @var $controllerFactory \Shift1\Core\Controller\Factory\ControllerFactory */
        $controllerAggregate = $controllerFactory->createController($data['_controller'], $data['_action'], $data);
        $response = $controllerAggregate->run();
        
        if(!($response instanceof ResponseInterface)) {
            throw new FrontControllerException('No response valid given: ' . \var_export($response, 1));
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
     * @param Service\Container\ServiceContainerInterface $serviceContainer
     * @return void
     */
    public function setServiceContainer(ServiceContainerInterface $serviceContainer) {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @throws \RuntimeException
     * @return Service\Container\ServiceContainerInterface
     */
    public function getServiceContainer() {
        if(!($this->serviceContainer instanceof Service\Container\ServiceContainerInterface)) {
            throw new \RuntimeException('No service container defined');
        }
        return $this->serviceContainer;
    }


}