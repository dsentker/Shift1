<?php
namespace Shift1\Core\View\Renderer;

use Shift1\Core\Exceptions\ViewRendererException as RendererException;
use Shift1\Core\InternalFilePath;
 
class PHPRenderer extends AbstractRenderer {

    public function render() {
        $file = $this->getTemplate();

        if(empty($file)) {
            \trigger_error('No view file given', \E_USER_ERROR);
        }

        $link = new InternalFilePath($file);

        if(!$link->exists()) {
            if($this->isThrowingExceptions()) {
                \trigger_error("View File {$viewFile} not found", \E_USER_ERROR);
            }
        }

        \ob_start(null);
        require $link->getAbsolutePath();
        return \ob_get_clean();
    }

}