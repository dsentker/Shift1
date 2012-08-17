<?php
namespace Shift1\Core\Controller\Factory;

use Shift1\Core\Response\ResponseInterface;
use Shift1\Core\FrontController;
use Shift1\Core\Service\ContainerAccess;
use Shift1\Core\Service\Container\ServiceContainerInterface;
use Shift1\Core\Bundle\Definition\ControllerDefinition;
use Shift1\Core\Bundle\Definition\ActionDefinition;

class ControllerFactory implements ControllerFactoryInterface, ContainerAccess {

    /**
     * @var ServiceContainerInterface
     */
    protected $container;

    public function setContainer(ServiceContainerInterface $container) {
        $this->container = $container;
    }

    public function getContainer() {
        return $this->container;
    }

    /**
     * @param $params
     * @param \ReflectionMethod $action
     * @return array
     */
    protected function mapParamsToActionArgs($params, \ReflectionMethod $action) {
        $actionParams = array();

        foreach($action->getParameters() as $param) {
            /** @var $param \ReflectionParameter */
            $paramPosition = $param->getPosition();

            if(isset($params[$param->getName()])) {
                $actionParams[$paramPosition] = $params[$param->getName()];
            } else {
                if($param->isDefaultValueAvailable()) {
                    /*
                     * Note that there is a strict behaviour in php's \ReflectionParameter->getDefaultValue().
                     * If the current default-value-parameter preprends an parameter without a default value,
                     * getDefaultValue() will return always FALSE.
                     * @see http://de3.php.net/manual/en/reflectionparameter.isdefaultvalueavailable.php#105207
                     */
                    $actionParams[$paramPosition] = $param->getDefaultValue();
                } else {
                    $actionParams[$paramPosition] = null;
                }
            }
        }

        return $actionParams;
    }

    /**
     * @param string $actionDefinition The action definition, e.g. vendor:bundleName:foo::bar
     * @param array $params
     * @return ControllerAggregate
     */
    public function createController($actionDefinition, array $params = array()) {

        $actionDefinition = new ActionDefinition($actionDefinition);
        $actionName = $actionDefinition->getActionName();
        $rfController = new \ReflectionClass($actionDefinition->getNamespace());

        /** @var $controller \Shift1\Core\Controller\AbstractController */
        $controller = $rfController->newInstanceArgs(array($params));
        $controller->addParam('_dispatchedDefinition', $actionDefinition);
        $controller->setContainer($this->getContainer());
        $actionParams = $this->mapParamsToActionArgs($params, new \ReflectionMethod($controller, $actionName));
        return new ControllerAggregate($controller, $actionName, $actionParams);

    }

}