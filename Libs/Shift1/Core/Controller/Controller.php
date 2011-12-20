<?php
namespace Shift1\Core\Controller;

use Shift1\Core\View\View;

class Controller extends AbstractController {

    /**
     * @var \Shift1\Core\View\View
     */
    protected $view;

    /**
     * @param array $params
     */
    final public function __construct(array $params = array()) {
        parent::__construct($params);

        $this->view = new View($params['_controller'] . '/' . $params['_action']);
        $this->init();
    }

    /**
     * @return \Shift1\Core\View\View
     */
    public function getView() {
        return $this->view;
    }

}