<?php
namespace Application\Services\ViewFilter;

use Shift1\Core\Service\AbstractService;

class EscapeService extends AbstractService  {

    public function __construct() {
        $this->setClassNamespace('\Shift1\Core\View\Filter\EscapeOutput');
    }

}