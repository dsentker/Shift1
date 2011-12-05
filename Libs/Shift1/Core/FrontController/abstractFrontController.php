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
        
        $controllerObject = new $controller($params);
        $controllerObject->setParams($params); // overload params again if the Controller is overridden

        $response = \call_user_func_array(array($controllerObject, $actionName), $params);

        if(!($response instanceof iResponse)) {
            $requestedControllerString = $controller . '::' . $actionName . ' ( ' . \implode(', ', $params) . ' )';
            throw new FrontControllerException('No valid Response given from Controller ' . $requestedControllerString);
        }

        $response->sendToClient();
    }

}
?>
