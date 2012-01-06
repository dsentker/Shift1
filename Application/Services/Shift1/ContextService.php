<?php
namespace Application\Services\Shift1;

use Shift1\Core\Service\AbstractService;


class ContextService extends AbstractService {

    public static $isSingleton = true;

    public function __construct() {
        $this->setClassNamespace('\Shift1\Core\Context\Context');
    }
}