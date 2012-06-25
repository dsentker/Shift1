<?php
namespace Application\Services\Shift1;

use Shift1\Core\Service\AbstractService;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;


class ControllerFactoryService extends AbstractService {

    public function __construct() {

        $this->setClassNamespace('\Shift1\Core\Controller\Factory\ControllerFactory');
        $this->necessitate('shift1.config');
    }

    public function initialize() {

        $this->setConstructorArgs(array(
                       $this->get('shift1.config')->controller,
                  ));

    }

}