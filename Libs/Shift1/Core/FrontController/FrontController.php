<?php
namespace Shift1\Core\FrontController;

use Shift1\Core\Service\Container\ServiceContainerInterface;
use Shift1\Core\FrontController\Exceptions\FrontControllerException;
use Shift1\Core\Response\ResponseInterface;
use Shift1\Core\Routing\Route\RouteInterface;
use Shift1\Core\Routing\Result\RoutingResult;
use Shift1\Core\Routing\Router\Router;
use Shift1\Core\Bundle\Definition\ActionDefinition;
use Shift1\Core\Bundle\Definition\CommandDefinition;

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
     * @return RoutingResult
     */
    protected function getRoutingResult(Router $router) {

        $routingResult = $router->getRequestData();

        if($this->validateRequestResult($routingResult) === false) {
            throw new FrontControllerException('No valid request result given.', FrontControllerException::REQUEST_NOT_VALID);
        }

        $paramFactory = $this->getServiceContainer()->get('paramConverterFactory');
        $routingResult->convertParams($paramFactory);
        return $routingResult;
    }

    public function executeConsole() {
        $router = $this->getServiceContainer()->get('cli-router');

        $routingResult = $this->getRoutingResult($router);
        $commandDefinition = new CommandDefinition($routingResult->getRoute()->getHandler());


        $controllerAggregate = $this->getControllerFactory()->createController($commandDefinition, $routingResult->getVars());
        echo $controllerAggregate->run();
    }

   /**
     * @throws Exceptions\FrontControllerException
     * @return void
     */
    public function executeHttp() {

        $router = $this->getServiceContainer()->get('router');

        $routingResult = $this->getRoutingResult($router);
        $actionDefinition = new ActionDefinition($routingResult->getRoute()->getHandler());
        
        $controllerAggregate = $this->getControllerFactory()->createController($actionDefinition, $routingResult->getVars());
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