<?php
namespace Shift1\Core\FrontController;

use Shift1\Core\Shift1Object;
use Shift1\Core\Request\iRequest;
use Shift1\Core\Exceptions\FrontControllerException;
use Shift1\Core\Controller\Factory\ControllerFactory;

abstract class AbstractFrontController extends Shift1Object implements iFrontController {

    /**
     * @var \Shift1\Core\Request\iRequest
     */
    protected $request;

    public function __construct(iRequest $request) {
        $this->request = $request;
    }

    /**
     * @return \Shift1\Core\Request\iRequest
     */
    protected function getRequest() {
        return $this->request;
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function validateRequestResult(array $data) {
        return (
          isset($data['_controller'])
          && isset($data['_action'])
        );
    }

    /**
     * @throws \Shift1\Core\Exceptions\FrontControllerException
     * @return string
     */
    public function run() {

        $request = $this->getRequest();
        $data = $request->assembleController();

        if($this->validateRequestResult($data) === false) {
            throw new FrontControllerException('No valid Request result given: ' . \PHP_EOL . \var_export($data, 1));
        }

        $response = ControllerFactory::createController($data['_controller'], $data['_action'], $data);

        return $response->sendToClient();
    }

}