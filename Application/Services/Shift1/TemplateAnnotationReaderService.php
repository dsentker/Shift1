<?php
namespace Application\Services\Shift1;

use Shift1\Core\Service\AbstractService;

class TemplateAnnotationReaderService extends AbstractService {

    public function __construct() {

        $this->setClassNamespace('\Shift1\Core\View\TemplateAnnotationReader\TemplateAnnotationReader');

    }

}