<?php
namespace Shift1\Core\Controller\Factory;

use Shift1\Core\Controller\ControllerInterface;

class ControllerAggregate {

    /**
     * @var ControllerInterface
     */
    protected $controller;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var array
     */
    protected $actionParams;

    /**
     * @param \Shift1\Core\Controller\ControllerInterface $controller
     * @param string $action
     * @param array $actionParams
     */
    public function __construct(ControllerInterface $controller, $action, array $actionParams) {
        $this->controller = $controller;
        $this->action = $action;
        $this->actionParams = $actionParams;
    }

    /**
     * @return \Shift1\Core\Controller\ControllerInterface|ControllerInterface
     */
    public function getController() {
        return $this->controller;
    }

    public function run() {
        return \call_user_func_array(array($this->getController(), $this->action), $this->actionParams);
    }
    
}
