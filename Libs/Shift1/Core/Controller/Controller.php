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

        $suggestedViewFile = $dispatched['class'] . '/' . $dispatched['action'];

        switch(true) {
            case $this->view->fileExists($suggestedViewFile):
                $this->getView()->setViewFile($suggestedViewFile);
                break;
            case $this->view->fileExists('index'):
                $this->getView()->setViewFile('index');
                break;
            default:
                // No view file detected
                $this->getView()->setViewFile('Libs/Shift1/Core/Resources/Views/viewNotFound', false);
        }

    }

}