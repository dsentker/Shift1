<?php
namespace Shift1\Core\FrontController;

use Shift1\Core\Service\Container\ServiceContainerInterface;
use Shift1\Core\FrontController\Exceptions\FrontControllerException;
use Shift1\Core\Response\ResponseInterface;
use Shift1\Core\Routing\Route\RouteInterface;
use Shift1\Core\Routing\Result\RoutingResult;

class FrontController {

    /**
     * @var null|ServiceContainerInterface
     */
    protected $serviceContainer;

    /**
     * @return \Shift1\Core\Controller\Factory\ControllerFactory
     */
    private function getControllerFactory() {
        return $this->getServiceContainer()->get('controllerFactory');
    }

   /**
     * @throws Exceptions\FrontControllerException
     * @return void
     */
    public function executeHttp() {

        $router = $this->getServiceContainer()->get('router');
        $request = $this->getServiceContainer()->get('request');
        $routingResult = $router->getDataFromUri($request->getAppRequestUri());

        if($this->validateRequestResult($routingResult) === false) {
            throw new FrontControllerException('No valid request result given.', FrontControllerException::REQUEST_NOT_VALID);
        }


        $paramFactory = $this->getServiceContainer()->get('paramConverterFactory');
        $routingResult->convertParams($paramFactory);
        
        $controllerAggregate = $this->getControllerFactory()->createController($routingResult->getRoute()->getHandler(), $routingResult->getVars());
        $response = $controllerAggregate->run();
        
        if(!($response instanceof ResponseInterface)) {
            throw new FrontControllerException('No valid response given: ' . \var_export($response, 1) . ', instance of ResponseInterface expected!', FrontControllerException::RESPONSE_NOT_VALID);
        }

        $response->sendToClient();
    }

    /**
     * @param RoutingResult $result
     * @return bool
     */
    protected function validateRequestResult(RoutingResult $result) {
        return ( $result->getRoute() instanceof RouteInterface );
    }

    /**
     * @param ServiceContainerInterface $serviceContainer
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
        if(!($this->serviceContainer instanceof ServiceContainerInterface)) {
            throw new \RuntimeException('No service container defined');
        }
        return $this->serviceContainer;
    }


}