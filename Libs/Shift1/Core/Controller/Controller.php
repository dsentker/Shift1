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
    public function init() {
        $this->view = new View();

        $dispatched = $this->getParam('_dispatched');

        $suggestedViewFile = $dispatched['class'] . '/' . $dispatched['action'];

        if($this->getView()->fileExists($suggestedViewFile)) {
            $this->getView()->setViewFile($suggestedViewFile);
        } elseif($this->getView()->fileExists('/index')) {
            $this->getView()->setViewFile('index');
        } else {
            // No view file detected
            $this->getView()->setViewFile('Libs/Shift1/Core/Resources/Views/viewNotFound', false);
        }

    }

}