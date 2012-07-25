<?php
namespace Application\ServiceLocator\Shift1;

use Shift1\Core\Service\Locator\AbstractServiceLocator;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;


class ViewRendererLocator extends AbstractServiceLocator {

    public function __construct() {
        $this->setClassNamespace('\Shift1\Core\View\Renderer\PHPRenderer');
    }

}