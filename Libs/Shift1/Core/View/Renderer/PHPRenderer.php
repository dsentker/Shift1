<?php
namespace Shift1\Core\View\Renderer;

use Shift1\Core\Exceptions\ViewRendererException as RendererException;
use Shift1\Core\View\ViewInterface;

class PHPRenderer extends AbstractRenderer {

    public function render(ViewInterface $view) {

        $template = $view->getViewFile();

        if(empty($template)) {
            \trigger_error('No view file given', \E_USER_ERROR);
        }

        if(!$template->exists()) {
            $name = $template->getPath();
            $errorMessage = "View File {$name} not found";
            if($view->isThrowingExceptions()) {
                throw new RendererException($errorMessage);
            } else {
                \trigger_error($errorMessage, \E_USER_ERROR);
            }
        }

        $request = $view->getContainer()->get('shift1.request');
        $router  = $view->getContainer()->get('shift1.router');
        $vars    = $view->getVariableSet();

        \ob_start(null);
        require $template->getAbsolutePath();
        return \ob_get_clean();
    }

    /**
     * @return string
     */
    public function getName() {
        return 'PHPRenderer';
    }

}