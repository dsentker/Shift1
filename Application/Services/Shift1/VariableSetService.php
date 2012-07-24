<?php
namespace Application\Services\Shift1;

use Shift1\Core\Service\AbstractService;

class VariableSetService extends AbstractService {

    public function __construct() {
        $this->setClassNamespace('\Shift1\Core\View\VariableSet\FilteredVariableSet');
    }

}