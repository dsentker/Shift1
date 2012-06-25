<?php
namespace Shift1\Core\View\Renderer;

use Shift1\Core\Exceptions\ViewRendererException as RendererException;
use Shift1\Core\View\ViewInterface;
use Shift1\Core\InternalFilePath;
 
class PHPRenderer extends AbstractRenderer {

    public function render(ViewInterface $view) {

        $template = $view->getViewFile();

        if(empty($template)) {
            \trigger_error('No view file given', \E_USER_ERROR);
        }
        $link = new InternalFilePath($template);
        if(!$link->exists()) {
            if($view->isThrowingExceptions()) {
                \trigger_error("View File {$template} not found", \E_USER_ERROR);
            }
        }

        $request = $this->getContainer()->get('shift1.request');
        $router = $this->getContainer()->get('shift1.router');

        \ob_start(null);
        require $link->getAbsolutePath();
        return \ob_get_clean();
    }

    /**
     * @return string
     */
    public function getName() {
        return 'PHPRenderer';
    }

}