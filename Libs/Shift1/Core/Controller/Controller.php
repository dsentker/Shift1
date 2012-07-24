<?php
namespace Shift1\Core\Controller;

use Shift1\Core\View\View;

class Controller extends AbstractController {

    /**
     * @var \Shift1\Core\View\View
     */
    protected $view;

    /**
     * Prepares the view instance
     * @return void
     */
    public function initView() {

        $this->view = $this->get('shift1.view');
        $dispatched = $this->getParam('_dispatched');
        $this->view->setActionView($dispatched['class'], $dispatched['action']);

    }

}