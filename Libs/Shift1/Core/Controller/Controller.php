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

        /** @var $dispatchedDefinition \Shift1\Core\Bundle\Definition\ActionDefinition  */
        $dispatchedDefinition = $this->getParam('_dispatchedDefinition');
        $this->view = $this->get('view');

        $templateDefinition = $dispatchedDefinition->getTemplateDefinition();
        $suggestedView = $templateDefinition->getTemplateFilePath($dispatchedDefinition->getControllerName(false));
        $this->view->setViewFile($suggestedView);

    }

}