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
    }

    public function init() {
        $this->view = new View();

        $dispatched = $this->getParam('_dispatched');

        $suggestedViewFile = $dispatched['class'] . '/' . $dispatched['action'];


        #die($viewDir . '/' . $suggestedViewFile);
        #die(var_dump($this->getView()->fileExists($viewDir . '/' . $suggestedViewFile)));

        if($this->getView()->fileExists($suggestedViewFile)) {
            $this->getView()->setViewFile($suggestedViewFile);
        } elseif($this->getView()->fileExists('/index')) {
            $this->getView()->setViewFile('index');
        } else {
            // No view file detected
            $this->getView()->setViewFile('Libs/Shift1/Core/Resources/Views/viewNotFound', false);
        }

    }

    /**
     * @return \Shift1\Core\View\View
     */
    public function getView() {
        return $this->view;
    }

}