<?php
namespace Shift1\Core\FrontController;

use Shift1\Core\Dispatcher\Dispatcher;
use Shift1\Core\Response\iResponse;
use Shift1\Core\Exceptions\FrontControllerException;
use Shift1\Core\Exceptions\ApplicationException;
use Shift1\Core\Debug\HTMLResponseExceptionHandler;
use Shift1\Core\Shift1Object;

abstract class AbstractFrontController extends Shift1Object implements iFrontController {

    /**
     * @var \Shift1\Core\Dispatcher\Dispatcher
     */
    protected $dispatcher;

    /**
     * @return \Shift1\Core\Dispatcher\Dispatcher
     */
    public function getDispatcher() {
        return $this->dispatcher;
    }

    /**
     * @param \Shift1\Core\Dispatcher\Dispatcher $dispatcher
     * @return void
     */
    public function setDispatcher(Dispatcher $dispatcher) {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param $uriParams
     * @param \ReflectionMethod $action
     * @return array
     */
    protected function mapToActionParams($uriParams, \ReflectionMethod $action) {
        $actionParams = array();

        foreach($action->getParameters() as $param) {
            /** @var \ReflectionParameter $param */

            $paramPosition = $param->getPosition();

            if(isset($uriParams[$param->getName()])) {
                $actionParams[$paramPosition] = $uriParams[$param->getName()];
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
     * @throws \Shift1\Core\Exceptions\FrontControllerException
     * @return void
     */
    public function run() {

        /**
         * @var array $controllerData
         * @var \Shift1\Core\Response\iResponse $response
         */
        $controllerData = $this->getDispatcher()->dispatch();
        $controller = $controllerData['controllerClass'];
        $actionName = $controllerData['actionMethod'];
        $params = $controllerData['params'];

        try {
            $reflectionAction = new \ReflectionMethod($controller, $actionName);
        } catch(\ReflectionException $e) {
            throw new FrontControllerException('Reflection failed: Controller `' . $controller . '::' . $actionName .'´ does not exist!');
        }

        $actionparams = $this->mapToActionParams($params, $reflectionAction );
        
        try {
            $response = \call_user_func_array(array(new $controller($params), $actionName), $actionparams);
        } catch(ApplicationException $e) {
            $handler = new HTMLResponseExceptionHandler;
            $handler->handle($e);
            exit(0);
        }

        if(!($response instanceof iResponse)) {
            $requestedControllerString = $controller . '::' . $actionName . ' ( ' . \implode(', ', $params) . ' )';
            throw new FrontControllerException('No valid Response given from Controller ' . $requestedControllerString);
        }

        $response->sendToClient();
    }

}