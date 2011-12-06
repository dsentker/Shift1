<?php
namespace Shift1\Core\FrontController;

use Shift1\Core\Dispatcher\Dispatcher;
use Shift1\Core\Response\iResponse;
use Shift1\Core\Exceptions\FrontControllerException;
use Shift1\Core\Shift1Object;

abstract class AbstractFrontController extends Shift1Object implements iFrontController {

    protected $router;

    protected $dispatcher;

    public function getDispatcher() {
        return $this->dispatcher;
    }

    public function setDispatcher(Dispatcher $dispatcher) {
        $this->dispatcher = $dispatcher;
    }

    public function run() {

        $controllerData = $this->getDispatcher()->dispatch();
        $controller = $controllerData['controllerClass'];
        $actionName = $controllerData['actionMethod'];
        $params = $controllerData['params'];
        $actionParams = array();
        $requestedParams = array();
        $reflectionMethod = new \ReflectionMethod($controller, $actionName);

        foreach($reflectionMethod->getParameters() as $param) {
            /** @var \ReflectionParameter $param */
            $requestedParams[$param->getPosition()] = $param;
        }

        foreach($requestedParams as $position => $reflectionParameter) {
            /** @var \ReflectionParameter $reflectionParameter; */
            if(isset($params[$reflectionParameter->getName()])) {
                $actionParams[$position] = $params[$reflectionParameter->getName()];
            } else {
                if($reflectionParameter->isDefaultValueAvailable()) {
                    /*
                     * Note that there is a strict behaviour in php's \ReflectionParameter->getDefaultValue().
                     * If the current default-value-parameter preprends an parameter without a default value,
                     * getDefaultValue() will return always FALSE.
                     * @see http://de3.php.net/manual/en/reflectionparameter.isdefaultvalueavailable.php#105207
                     */
                    $actionParams[$position] = $reflectionParameter->getDefaultValue();
                } else {
                    $actionParams[$position] = null;
                }
            }
        }

        $controllerObject = new $controller($params);
        $controllerObject->setParams($params); // overload params again if Controller's contructor was overridden

        $response = \call_user_func_array(array($controllerObject, $actionName), $actionParams);

        if(!($response instanceof iResponse)) {
            $requestedControllerString = $controller . '::' . $actionName . ' ( ' . \implode(', ', $params) . ' )';
            throw new FrontControllerException('No valid Response given from Controller ' . $requestedControllerString);
        }

        $response->sendToClient();
    }

    protected function getRequestedActionParams($className, $methodName) {
        $requestedParams = array();
        $reflectionMethod = new \ReflectionMethod($className, $methodName);
        foreach($reflectionMethod->getParameters() as $param) {
            /** @var \ReflectionParameter $param */
            $requestedParams[$param->getPosition()] = $param->getName();
        }
        return $requestedParams;
    }

}
?>
