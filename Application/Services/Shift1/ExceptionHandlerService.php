<?php
namespace Application\Services\Shift1;

use Shift1\Core\Service\AbstractService;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;


class ExceptionHandlerService extends AbstractService {

    public static $isSingleton = true;

    public function __construct() {
        $this->necessitate('shift1.context');
        $this->necessitate('shift1.view');

    }

    public function initialize() {

        $handlerNS = '\\Shift1\\Core\Debug\\';

        switch($this->get('shift1.context')->environment) {
            case 'production':
                $handlerNS .= 'SilentExceptionHandler';
                break;
            default:
                $handlerNS .= 'HTMLResponseExceptionHandler';
        }

        $this->setClassNamespace($handlerNS);

    }
}