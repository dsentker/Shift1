<?php
namespace Application\Services\ViewHelper;

use Shift1\Core\Service\AbstractService;

class EscapeOutputService extends AbstractService  {

    public static $isSingleton = true;

    public function __construct() {
        $this->setClassNamespace('\Shift1\Core\View\Helper\EscapeOutput');
    }

}