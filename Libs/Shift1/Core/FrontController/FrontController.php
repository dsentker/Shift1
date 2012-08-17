<?php
namespace Shift1\Core\FrontController;

use Shift1\Core\Service\Container\ServiceContainerInterface;
use Shift1\Core\Controller\Factory\ControllerFactory;
use Shift1\Core\FrontController\Exceptions\FrontControllerException;
use Shift1\Core\Response\ResponseInterface;
use Shift1\Core\Router\AbstractRouter;
use Shift1\Core\Routing\Route\RouteInterface;
use Shift1\Core\View\View;


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
     * @return string
     */
    public function executeConsole() {
        $request = $this->getServiceContainer()->get('request');
        if(!$request->isCli()) {
            throw new FrontControllerException('No CLI Environment detected.', FrontControllerException::CLI_NOT_RUNNING);
        }

        /** @var $router \Shift1\Core\Router\RouterInterface */
        $router = $this->getServiceContainer()->get('cli-router');
        $data = $router->resolve();
        if($this->validateRequestResult($data) === false) {
            throw new FrontControllerException('No valid request result given: ' . \var_export($data, 1), FrontControllerException::REQUEST_NOT_VALID);
        }

        $controllerAggregate = $this->getControllerFactory()->createController($data['_bundle'], $data['_controller'], $data['_action'], $data);
        echo $controllerAggregate->run();


    }

    /**
     * @throws Exceptions\FrontControllerException
     * @return void
     */
    public function executeHttp() {

        $router = $this->getServiceContainer()->get('router');
        $request = $this->getServiceContainer()->get('request');
        $data = $router->getDataFromUri($request->getAppRequestUri());

        /*
        if($this->validateRequestResult($data) === false) {
            throw new FrontControllerException('No valid request result given: ' . \var_export($data, 1), FrontControllerException::REQUEST_NOT_VALID);
        }
        */

        $route = $data['_route'];
        /** @var $route RouteInterface */

        $opts = $route->getParamOptions();
        $paramFactory = $this->getServiceContainer()->get('paramConverterFactory');
        /** @var $paramFactory \Shift1\Core\Routing\ParamConverter\Factory\ParamConverterFactory */

        foreach($data as $paramKey => &$paramValue) {
            if(\is_string($paramValue)) {
                if(isset($opts['@' . $paramKey]['paramConverter'])) {
                    $converter = $paramFactory->createConverter($opts['@' . $paramKey]['paramConverter']);
                    /** @var $converter \Shift1\Core\Routing\ParamConverter\AbstractParamConverter */
                    $paramValue = $converter->getActionParam($paramValue);
                }
            }
        }

        #echo '<pre>';
        #die(print_r($data));

        $controllerAggregate = $this->getControllerFactory()->createController($route->getHandler(), $data);
        $response = $controllerAggregate->run();
        
        if(!($response instanceof ResponseInterface)) {
            throw new FrontControllerException('No valid response given: ' . \var_export($response, 1) . ', instance of ResponseInterface expected!', FrontControllerException::RESPONSE_NOT_VALID);
        }

        $response->sendToClient();
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function validateRequestResult(array $data) {
        return ( isset($data['_route']) && $data['_route'] instanceof RouteInterface );
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