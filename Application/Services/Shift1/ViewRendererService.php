<?php
namespace Application\Services\Shift1;

use Shift1\Core\Service\AbstractService;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;


class ViewRendererService extends AbstractService {

    public function __construct() {
        $this->setClassNamespace('\Shift1\Core\View\Renderer\PHPRenderer');
    }

}